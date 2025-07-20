<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Pitch;
use App\Models\Booking;
use App\Models\Stadium;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;

class BookingService
{
    /**
     * Get available slots for a specific pitch on a given date.
     */
    public function getAvailableSlots(int $pitchId, string $date): array
    {
        $pitch = Pitch::with('stadium')->findOrFail($pitchId);
        
        if ($pitch->status !== 'available') {
            return [];
        }

        return $pitch->getAvailableSlots($date);
    }

    /**
     * Get available slots for all pitches in a stadium on a given date.
     */
    public function getStadiumAvailableSlots(int $stadiumId, string $date): array
    {
        $stadium = Stadium::with(['pitches' => function ($query) {
            $query->where('status', 'available');
        }])->findOrFail($stadiumId);

        $result = [];
        foreach ($stadium->pitches as $pitch) {
            $slots = $pitch->getAvailableSlots($date);
            if (!empty($slots)) {
                $result[] = [
                    'pitch_id' => $pitch->id,
                    'pitch_name' => $pitch->name,
                    'pitch_type' => $pitch->type,
                    'pitch_surface' => $pitch->surface,
                    'available_slots' => $slots,
                ];
            }
        }

        return $result;
    }

    /**
 * Create a new booking.
 */
public function createBooking(array $bookingData): Booking
{
    $pitch = Pitch::findOrFail($bookingData['pitch_id']);
    
    // Validate input data
    $validatedData = $this->validateBookingData($bookingData);

    // Check if the time slot is available
    if (!$pitch->isTimeSlotAvailable(
        $validatedData['booking_date'],
        $validatedData['start_time'],
        $validatedData['end_time']
    )) {
        throw new Exception('This time slot is already booked or conflicts with another booking.');
    }

    // Calculate total price based on duration
    $totalPrice = $validatedData['duration_minutes'] === 90 
        ? $pitch->hourly_rate_90 
        : $pitch->hourly_rate_60;

    // Use database transaction to prevent race conditions
    return DB::transaction(function () use ($validatedData, $totalPrice, $pitch) {
        $booking = Booking::create([
            'pitch_id' => $validatedData['pitch_id'],
            'user_name' => $validatedData['user_name'],
            'user_email' => $validatedData['user_email'],
            'user_phone' => $validatedData['user_phone'],
            'booking_date' => $validatedData['booking_date'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
            'duration_minutes' => $validatedData['duration_minutes'],
            'total_price' => $totalPrice,
            'status' => 'confirmed',
            'notes' => $validatedData['notes'] ?? null,
        ]);

        return $booking->load(['pitch.stadium']);
    });
}

/**
 * Validate booking data.
 */
private function validateBookingData(array $bookingData): array
{
    $validator = Validator::make($bookingData, [
        'pitch_id' => 'required|exists:pitches,id',
        'user_name' => 'required|string|max:255',
        'user_email' => 'required|email|max:255',
        'user_phone' => 'required|string|max:20',
        'booking_date' => 'required|date|after_or_equal:today',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
        'duration_minutes' => 'required|in:60,90',
        'notes' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        throw new Exception('Invalid booking data: ' . implode(', ', $validator->errors()->all()));
    }

    return $validator->validated();
}

    /**
     * Cancel a booking.
     */
    public function cancelBooking(int $bookingId): Booking
    {
        $booking = Booking::findOrFail($bookingId);

        if (!$booking->canBeCancelled()) {
            throw new Exception('This booking cannot be cancelled.');
        }

        $booking->update(['status' => 'cancelled']);

        return $booking->load(['pitch.stadium']);
    }

    /**
     * Get all bookings for a specific date.
     */
    public function getBookingsForDate(string $date): Collection
    {
        return Booking::with(['pitch.stadium'])
            ->where('booking_date', $date)
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Get upcoming bookings for a user.
     */
    public function getUserBookings(string $userEmail): Collection
    {
        return Booking::with(['pitch.stadium'])
            ->where('user_email', $userEmail)
            ->where('status', '!=', 'cancelled')
            ->where('booking_date', '>=', Carbon::today())
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->get();
    }
} 