<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Pitch;
use Carbon\Carbon;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'pitch_id' => 'required|exists:pitches,id',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255',
            'user_phone' => 'required|string|max:20',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|string|date_format:H:i',
            'end_time' => 'required|string|date_format:H:i',
            'duration_minutes' => 'required|integer|in:60,90',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // Validate end time is after start time
            $this->validateEndTimeAfterStartTime($validator);
            
            // Validate pitch exists and is available
            $this->validatePitchAvailable($validator);
            
            // Validate slot type matches pitch requirements
            $this->validateSlotType($validator);
            
            // Validate booking is within operating hours
            $this->validateOperatingHours($validator);
            
            // Validate booking is on operating days
            $this->validateOperatingDays($validator);
            
            // Validate slot availability (no conflicts)
            $this->validateSlotAvailability($validator);
        });
    }

    /**
     * Validate that end time is after start time.
     */
    protected function validateEndTimeAfterStartTime($validator)
    {
        if (!$this->start_time || !$this->end_time) {
            return;
        }

        try {
            $startTime = Carbon::parse($this->start_time);
            $endTime = Carbon::parse($this->end_time);

            if ($endTime->lte($startTime)) {
                $validator->errors()->add('end_time', 'End time must be after start time.');
            }
        } catch (\Exception $e) {
            // If time parsing fails, the regex validation will have caught it
            return;
        }
    }

    /**
     * Validate that the pitch exists and is available.
     */
    protected function validatePitchAvailable($validator)
    {
        if (!$this->pitch_id) {
            return;
        }

        $pitch = Pitch::find($this->pitch_id);
        
        if ($pitch && $pitch->status !== 'available') {
            $validator->errors()->add('pitch_id', 'This pitch is currently not available for booking.');
        }
    }

    /**
     * Validate that booking times are within pitch operating hours.
     */
    protected function validateOperatingHours($validator)
    {
        if (!$this->pitch_id || !$this->start_time || !$this->end_time) {
            return;
        }

        $pitch = Pitch::find($this->pitch_id);
        
        if (!$pitch) {
            return;
        }

        try {
            $startTime = Carbon::parse($this->start_time);
            $endTime = Carbon::parse($this->end_time);
            $operatingStart = Carbon::parse($pitch->operating_start_time);
            $operatingEnd = Carbon::parse($pitch->operating_end_time);

            if ($startTime->lt($operatingStart) || $endTime->gt($operatingEnd)) {
                $validator->errors()->add('start_time', 
                    "Booking must be within operating hours: {$operatingStart->format('H:i')} - {$operatingEnd->format('H:i')}");
            }
        } catch (\Exception $e) {
            // If time parsing fails, let the basic validation rules handle it
            return;
        }
    }

    /**
     * Validate that booking times are on operating days.
     */
    protected function validateOperatingDays($validator)
    {
        if (!$this->pitch_id || !$this->booking_date) {
            return;
        }

        $pitch = Pitch::find($this->pitch_id);
        
        if (!$pitch || !$pitch->operating_days) {
            return;
        }

        try {
            $dayOfWeek = strtolower(Carbon::parse($this->booking_date)->format('l'));
            
            if (!in_array($dayOfWeek, $pitch->operating_days)) {
                $availableDays = implode(', ', array_map('ucfirst', $pitch->operating_days));
                $validator->errors()->add('booking_date', 
                    "This pitch only operates on: {$availableDays}");
            }
        } catch (\Exception $e) {
            // If date parsing fails, let the basic validation rules handle it
            return;
        }
    }

    /**
     * Validate that the slot type matches the pitch slot type.
     */
    protected function validateSlotType($validator)
    {
        if (!$this->pitch_id || !$this->duration_minutes) {
            return;
        }

        $pitch = Pitch::find($this->pitch_id);
        
        if (!$pitch) {
            return;
        }

        // Check if duration matches the pitch's allowed slot type
        if ($pitch->slot_type === '60' && $this->duration_minutes != 60) {
            $validator->errors()->add('duration_minutes', 'This pitch only allows 60-minute bookings.');
        } elseif ($pitch->slot_type === '90' && $this->duration_minutes != 90) {
            $validator->errors()->add('duration_minutes', 'This pitch only allows 90-minute bookings.');
        }
    }

    /**
     * Validate that the time slot is available (not already booked).
     */
    protected function validateSlotAvailability($validator)
    {
        if (!$this->pitch_id || !$this->booking_date || !$this->start_time || !$this->end_time) {
            return;
        }

        $pitch = Pitch::find($this->pitch_id);
        
        if (!$pitch) {
            return;
        }

        try {
            $isAvailable = $pitch->isTimeSlotAvailable(
                $this->booking_date,
                $this->start_time,
                $this->end_time
            );

            if (!$isAvailable) {
                $validator->errors()->add('slot', 'This time slot is already booked.');
            }
        } catch (\Exception $e) {
            // If validation fails, let it pass and let the database constraint catch it
            return;
        }
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'pitch_id.exists' => 'The selected pitch does not exist.',
            'booking_date.after_or_equal' => 'Booking date must be today or in the future.',
            'start_time.date_format' => 'Start time must be in HH:MM format (e.g., 09:30).',
            'end_time.date_format' => 'End time must be in HH:MM format (e.g., 10:30).',
            'end_time.after' => 'End time must be after start time.',
            'duration_minutes.in' => 'Duration must be either 60 or 90 minutes.',
        ];
    }
}
