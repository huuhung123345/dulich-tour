<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\Payment;
use App\Models\Tour;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'tours'    => Tour::count(),
            'bookings' => Booking::count(),
            'pending'  => Booking::where('status', 'PENDING')->count(),
            'users'    => User::where('role_id', 3)->count(),
            'contacts' => Contact::count(),
            'revenue'  => Payment::where('status', 'SUCCESS')->sum('amount'),
        ]);
    }
}
