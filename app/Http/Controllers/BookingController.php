<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Departure;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function create(Departure $departure)
    {
        $departure->load('tour');
        abort_if($departure->status !== 'OPEN' || $departure->available_seats < 1, 404);

        return view('booking.create', compact('departure'));
    }

    public function store(Request $request, Departure $departure)
    {
        $data = $request->validate([
            'adult_count'   => 'required|integer|min:1|max:20',
            'child_count'   => 'nullable|integer|min:0|max:20',
            'contact_name'  => 'required|string|max:100',
            'contact_phone' => 'required|string|max:15',
            'note'          => 'nullable|string|max:500',
            'promo_code'    => 'nullable|string|max:30',
            'method'        => 'required|in:BANK,MOMO,VNPAY,CASH',
            'passengers'    => 'nullable|array',
            'passengers.*'  => 'nullable|string|max:100',
        ]);

        $adults   = (int) $data['adult_count'];
        $children = (int) ($data['child_count'] ?? 0);
        $people   = $adults + $children;

        try {
            DB::transaction(function () use ($request, $data, $departure, $adults, $children, $people) {
                // Khoa dong lich khoi hanh de tranh dat vuot so cho khi nhieu nguoi dat cung luc
                $dep = Departure::lockForUpdate()->findOrFail($departure->departure_id);
                $tour = $dep->tour;

                if ($dep->status !== 'OPEN' || $dep->start_date->lt(now()->startOfDay())) {
                    throw new \RuntimeException('Lịch khởi hành này đã đóng hoặc đã qua.');
                }
                if ($dep->available_seats < $people) {
                    throw new \RuntimeException('Chỉ còn ' . $dep->available_seats . ' chỗ trống, không đủ cho ' . $people . ' người.');
                }

                $subtotal = $adults * $tour->adult_price + $children * $tour->child_price;
                $discount = 0;
                $promoId = null;

                if ($code = strtoupper(trim((string) ($data['promo_code'] ?? '')))) {
                    $promo = Promotion::lockForUpdate()->where('code', $code)->first();
                    $today = now()->startOfDay();
                    if (!$promo || $today->lt($promo->start_date) || $today->gt($promo->end_date)) {
                        throw new \RuntimeException('Mã giảm giá không hợp lệ hoặc đã hết hạn.');
                    }
                    if ($promo->usage_limit !== null && $promo->used_count >= $promo->usage_limit) {
                        throw new \RuntimeException('Mã giảm giá đã hết lượt sử dụng.');
                    }
                    if ($subtotal < $promo->min_order_value) {
                        throw new \RuntimeException('Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($promo->min_order_value, 0, ',', '.') . ' đ để dùng mã này.');
                    }
                    $discount = $promo->discount_type === 'PERCENT'
                        ? round($subtotal * $promo->discount_value / 100)
                        : $promo->discount_value;
                    $discount = min($discount, $subtotal);
                    $promoId = $promo->promotion_id;
                    $promo->increment('used_count');
                }

                $booking = Booking::create([
                    'booking_code'    => 'BK' . now()->format('ymdHis') . random_int(100, 999),
                    'user_id'         => $request->user()->user_id,
                    'departure_id'    => $dep->departure_id,
                    'promotion_id'    => $promoId,
                    'adult_count'     => $adults,
                    'child_count'     => $children,
                    'subtotal'        => $subtotal,
                    'discount_amount' => $discount,
                    'total_amount'    => $subtotal - $discount,
                    'contact_name'    => $data['contact_name'],
                    'contact_phone'   => $data['contact_phone'],
                    'note'            => $data['note'] ?? null,
                    'status'          => 'PENDING',
                    'created_at'      => now(),
                ]);

                $names = $data['passengers'] ?? [];
                for ($i = 0; $i < $people; $i++) {
                    $name = trim((string) ($names[$i] ?? ''));
                    if ($name === '') {
                        $name = $i === 0 ? $data['contact_name'] : 'Hành khách ' . ($i + 1);
                    }
                    Passenger::create([
                        'booking_id'     => $booking->booking_id,
                        'full_name'      => $name,
                        'passenger_type' => $i < $adults ? 'ADULT' : 'CHILD',
                    ]);
                }

                Payment::create([
                    'booking_id' => $booking->booking_id,
                    'method'     => $data['method'],
                    'amount'     => $booking->total_amount,
                    'status'     => 'PENDING',
                ]);

                $dep->available_seats -= $people;
                if ($dep->available_seats === 0) {
                    $dep->status = 'FULL';
                }
                $dep->save();
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->withErrors(['booking' => $e->getMessage()]);
        }

        return redirect()->route('booking.mine')->with('ok', 'Đặt tour thành công! Vui lòng chờ xác nhận thanh toán.');
    }

    public function mine(Request $request)
    {
        $bookings = Booking::with(['departure.tour', 'payments'])
            ->where('user_id', $request->user()->user_id)
            ->orderByDesc('booking_id')
            ->get();

        return view('booking.mine', compact('bookings'));
    }

    public function cancel(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== $request->user()->user_id, 403);

        if (!in_array($booking->status, ['PENDING', 'PAID', 'CONFIRMED'])) {
            return back()->withErrors(['booking' => 'Đơn này không thể hủy.']);
        }

        DB::transaction(function () use ($booking) {
            $dep = Departure::lockForUpdate()->findOrFail($booking->departure_id);
            $dep->available_seats += $booking->adult_count + $booking->child_count;
            if ($dep->status === 'FULL') {
                $dep->status = 'OPEN';
            }
            $dep->save();

            $booking->update(['status' => 'CANCELLED']);
            Payment::where('booking_id', $booking->booking_id)->where('status', 'PENDING')->update(['status' => 'FAILED']);
        });

        return back()->with('ok', 'Đã hủy đơn ' . $booking->booking_code . '.');
    }
}
