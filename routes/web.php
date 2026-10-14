<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

// Trang cong khai
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tours/{tour}', [HomeController::class, 'show'])->name('tours.show');
Route::get('/lien-he', [ContactController::class, 'create'])->name('contact');
Route::post('/lien-he', [ContactController::class, 'store'])->name('contact.store');

// Dang nhap / dang ky
Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/dang-nhap', [AuthController::class, 'login'])->name('login.post');
    Route::get('/dang-ky', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/dang-ky', [AuthController::class, 'register'])->name('register.post');
});
Route::post('/dang-xuat', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Khach hang: dat tour
Route::middleware('auth')->group(function () {
    Route::get('/dat-tour/{departure}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/dat-tour/{departure}', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/don-cua-toi', [BookingController::class, 'mine'])->name('booking.mine');
    Route::post('/don-cua-toi/{booking}/huy', [BookingController::class, 'cancel'])->name('booking.cancel');
});

// Quan tri
Route::prefix('admin')->name('admin.')->middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('tours', Admin\TourController::class)->except(['show']);
    Route::post('tours/{tour}/departures', [Admin\DepartureController::class, 'store'])->name('departures.store');
    Route::delete('departures/{departure}', [Admin\DepartureController::class, 'destroy'])->name('departures.destroy');

    Route::get('bookings', [Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::post('bookings/{booking}/status', [Admin\BookingController::class, 'updateStatus'])->name('bookings.status');

    Route::get('contacts', [Admin\ContactController::class, 'index'])->name('contacts.index');
    Route::delete('contacts/{contact}', [Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
});
