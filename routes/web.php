<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\StadiumWebController;
use App\Http\Controllers\Web\BookingController as WebBookingController;

// Redirect root to stadiums index
Route::get('/', function () {
    return redirect()->route('stadiums.index');
});

// Stadium Web Routes
Route::prefix('stadiums')->name('stadiums.')->group(function () {
    Route::get('/', [StadiumWebController::class, 'index'])->name('index');
    Route::get('/{stadium}', [StadiumWebController::class, 'show'])->name('show');
});

// Booking Web Routes
Route::prefix('booking')->name('booking.')->group(function () {
    Route::post('/store', [WebBookingController::class, 'store'])->name('store');
    Route::get('/form', [StadiumWebController::class, 'booking'])->name('form');
    Route::get('/success/{booking}', [StadiumWebController::class, 'bookingSuccess'])->name('success');
});

// User Bookings
Route::get('/my-bookings', [WebBookingController::class, 'index'])->name('user.bookings');

require __DIR__ . '/admin.php';