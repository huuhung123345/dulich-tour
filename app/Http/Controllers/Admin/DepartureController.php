<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departure;
use App\Models\Tour;
use Illuminate\Http\Request;

class DepartureController extends Controller
{
    public function store(Request $request, Tour $tour)
    {
        $data = $request->validate([
            'start_date'  => 'required|date|after_or_equal:today',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'total_seats' => 'required|integer|min:1|max:500',
        ]);

        Departure::create($data + [
            'tour_id'         => $tour->tour_id,
            'available_seats' => $data['total_seats'],
            'status'          => 'OPEN',
        ]);

        return back()->with('ok', 'Đã thêm lịch khởi hành.');
    }

    public function destroy(Departure $departure)
    {
        if ($departure->bookings()->exists()) {
            return back()->withErrors(['departure' => 'Lịch khởi hành đã có đơn đặt, không thể xóa.']);
        }
        $departure->delete();

        return back()->with('ok', 'Đã xóa lịch khởi hành.');
    }
}
