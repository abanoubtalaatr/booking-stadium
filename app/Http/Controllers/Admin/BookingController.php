<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Filters\BookingFilter;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['pitch.stadium']);

        $bookings = (new BookingFilter($request))->apply($query)->latest()->paginate(config('app.per_page'));

        return view('admin.bookings.index', compact('bookings'));
    }
}
