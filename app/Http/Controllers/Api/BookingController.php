<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BookingRequest;
use App\Http\Requests\StoreBookingRequest;

class BookingController extends Controller
{
    protected BookingService $bookingService;
    use ApiResponse;
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Display a listing of bookings for a specific date.
     */
    public function index(BookingRequest $request): JsonResponse
    {
        $bookings = $this->bookingService->getBookingsForDate($request->date);

        return $this->success($bookings, 'Bookings retrieved successfully');
    }

    /**
     * Store a newly created booking.
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        try {
            $booking = $this->bookingService->createBooking($request->validated());

            return $this->success($booking, 'Booking created successfully');
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database constraint violations (like duplicate bookings)
            if (str_contains($e->getMessage(), 'UNIQUE constraint failed') && 
                str_contains($e->getMessage(), 'bookings.pitch_id, bookings.booking_date, bookings.start_time')) {
                return $this->error('The given data was invalid.', 422);
            }
            
            return $this->error('Database error: ' . $e->getMessage(), 500);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    /**
     * Display the specified booking.
     */
    public function show(int $id): JsonResponse
    {
        $booking = Booking::with(['pitch.stadium'])->findOrFail($id);

        return $this->success($booking, 'Booking retrieved successfully');
    }

    /**
     * Get available slots for a specific pitch on a given date.
     */
    public function availableSlots(Request $request, int $pitchId): JsonResponse
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        try {
            $slots = $this->bookingService->getAvailableSlots($pitchId, $request->date);

            return $this->success([
                'pitch_id' => $pitchId,
                    'date' => $request->date,
                    'available_slots' => $slots
            ], 'Available slots retrieved successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
}
