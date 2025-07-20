<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StadiumController;
use App\Http\Controllers\Api\BookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Stadium API Routes

Route::apiResource('stadiums', StadiumController::class)->only(['index', 'show']);
// Booking API Routes
Route::apiResource('bookings', BookingController::class)->except(['destroy', 'update']);
// Pitch API Routes
Route::get('pitches/{pitch}/available-slots', [BookingController::class, 'availableSlots']);
