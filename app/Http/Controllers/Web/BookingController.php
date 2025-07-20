<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Services\BookingService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\BookingRequest;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        $email = $request->get('email');
        $bookings = [];

        if ($email) {
            $bookings = $this->bookingService->getUserBookings($email);
        }

        return view('bookings.user', compact('bookings', 'email'));
    }

    public function store(BookingRequest $request)
    {
        $booking = $this->bookingService->createBooking($request->validated());

        return redirect()->route('booking.success', $booking);
    }
}
