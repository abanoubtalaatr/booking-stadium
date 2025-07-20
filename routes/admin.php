<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\PitchController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\StadiumController;
use App\Http\Controllers\Admin\DashboardController;


// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Public admin routes (login)
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    
    // Protected admin routes
    Route::middleware('admin')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
      
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
       
        // Booking List
        Route::resource('bookings', BookingController::class)->only('index');
        
        // Stadium Management
        Route::resource('stadiums', StadiumController::class)->names('stadiums');
        
        // Pitch Management
        Route::resource('pitches', PitchController::class)->names('pitches');
    });
});
