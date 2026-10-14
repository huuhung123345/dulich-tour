<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Departure;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'departure.tour', 'payments']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $bookings = $query->orderByDesc('booking_id')->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'departure.tour', 'passengers', 'payments']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $data = $request->validate(['status' => 'required|in:' . implode(',', array_keys(Booking::STATUS))]);
        $new = $data['status'];
        $old = $booking->status;

        if ($new === $old) {
            return back();
        }

        try {
        DB::transaction(function () use ($booking, $new, $old) {
            $people = $booking->adult_count + $booking->child_count;

            // Huy don: tra lai cho. Kich hoat lai don da huy: tru lai cho.
            if ($new === 'CANCELLED' && $old !== 'CANCELLED') {
                $dep = Departure::lockForUpdate()->findOrFail($booking->departure_id);
                $dep->available_seats += $people;
                if ($dep->status === 'FULL') {
                    $dep->status = 'OPEN';
                }
                $dep->save();
                Payment::where('booking_id', $booking->booking_id)->where('status', 'PENDING')->update(['status' => 'FAILED']);
            } elseif ($old === 'CANCELLED' && $new !== 'CANCELLED') {
                $dep = Departure::lockForUpdate()->findOrFail($booking->departure_id);
                if ($dep->available_seats < $people) {
                    throw new \RuntimeException('Không đủ chỗ trống để khôi phục đơn này.');
                }
                $dep->available_seats -= $people;
                if ($dep->available_seats === 0) {
                    $dep->status = 'FULL';
                }
                $dep->save();
            }

            if (in_array($new, ['PAID', 'CONFIRMED', 'COMPLETED'])) {
                Payment::where('booking_id', $booking->booking_id)
                    ->whereIn('status', ['PENDING', 'FAILED'])
                    ->update(['status' => 'SUCCESS', 'paid_at' => now()]);
            }

            $booking->update(['status' => $new]);
        });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['booking' => $e->getMessage()]);
        }

        return back()->with('ok', 'Đã cập nhật trạng thái đơn ' . $booking->booking_code . '.');
    }
}
