<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\BookingRequest;
use App\Models\Stadium;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;

class StadiumWebController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Display the main dashboard.
     */
    public function index()
    {
        $stadiums = Stadium::active()
            ->with(['pitches' => function ($query) {
                $query->where('status', 'available');
            }])
            ->get();

        return view('stadiums.index', compact('stadiums'));
    }

    /**
     * Show stadium details and available slots.
     */
    public function show(Stadium $stadium, Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        
        $stadium->load(['pitches' => function ($query) {
            $query->where('status', 'available');
        }]);

        $availableSlots = $this->bookingService->getStadiumAvailableSlots($stadium->id, $date);

        return view('stadiums.show', compact('stadium', 'date', 'availableSlots'));
    }

    /**
     * Show booking form.
     */
    public function booking(Request $request)
    {
        $pitchId = $request->get('pitch_id');
        $date = $request->get('date');
        $startTime = $request->get('start_time');
        $endTime = $request->get('end_time');
        $duration = $request->get('duration');
        $price = $request->get('price');

        return view('bookings.create', compact('pitchId', 'date', 'startTime', 'endTime', 'duration', 'price'));
    }

    /**
     * Show booking success page.
     */
    public function bookingSuccess(Booking $booking)
    {
        $booking->load(['pitch.stadium']);
        return view('bookings.success', compact('booking'));
    }
}
