<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Pitch extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'stadium_id',
        'name',
        'type',
        'surface',
        'hourly_rate_60',
        'hourly_rate_90',
        'status',
        'capacity',
        'description',
        'amenities',
        'operating_start_time',
        'operating_end_time',
        'operating_days',
        'slot_type',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'hourly_rate_60' => 'decimal:2',
        'hourly_rate_90' => 'decimal:2',
        'capacity' => 'integer',
        'amenities' => 'json',
        'operating_days' => 'json',
        'operating_start_time' => 'datetime:H:i',
        'operating_end_time' => 'datetime:H:i',
    ];

    /**
     * Get the stadium that owns this pitch.
     */
    public function stadium(): BelongsTo
    {
        return $this->belongsTo(Stadium::class);
    }

    /**
     * Get all bookings for this pitch.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get bookings for a specific date.
     */
    public function bookingsForDate($date)
    {
        return $this->bookings()
            ->whereDate('booking_date', $date)
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time');
    }

    /**
     * Check if a time slot is available for booking.
     */
    public function isTimeSlotAvailable($date, $startTime, $endTime)
    {
        return !$this->bookings()
            ->whereDate('booking_date', $date)  // Use whereDate instead of where for date comparison
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startTime, $endTime) {
                // Check for any overlap: new booking overlaps with existing if:
                // 1. New start is before existing end AND new end is after existing start
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                });
            })
            ->exists();
    }

    /**
     * Get available time slots for a specific date.
     */
    public function getAvailableSlots($date)
    {
        $slots = [];
        
        // Check if the pitch operates on this day
        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
        if (!in_array($dayOfWeek, $this->operating_days ?? [])) {
            return $slots; // No slots if pitch doesn't operate on this day
        }
        
        // Get operating hours for this pitch
        $startTime = Carbon::parse($this->operating_start_time);
        $endTime = Carbon::parse($this->operating_end_time);
        
        $currentHour = $startTime->hour;
        $endHour = $endTime->hour;
        
        // Generate slots based on operating hours and slot type
        while ($currentHour < $endHour) {
            if ($this->slot_type === '60') {
                // Generate only 60-minute slots
                $start60 = sprintf('%02d:00', $currentHour);
                $end60 = sprintf('%02d:00', $currentHour + 1);
                
                if ($currentHour + 1 <= $endHour && $this->isTimeSlotAvailable($date, $start60, $end60)) {
                    $slots[] = [
                        'start_time' => $start60,
                        'end_time' => $end60,
                        'duration_minutes' => 60,
                        'price' => $this->hourly_rate_60,
                    ];
                }
                $currentHour++;
            } elseif ($this->slot_type === '90') {
                // Generate only 90-minute slots
                if ($currentHour + 1.5 <= $endHour) {
                    $start90 = sprintf('%02d:00', $currentHour);
                    $end90 = sprintf('%02d:30', $currentHour + 1);
                    
                    if ($this->isTimeSlotAvailable($date, $start90, $end90)) {
                        $slots[] = [
                            'start_time' => $start90,
                            'end_time' => $end90,
                            'duration_minutes' => 90,
                            'price' => $this->hourly_rate_90,
                        ];
                    }
                }
                $currentHour += 1.5; // Move by 1.5 hours for 90-minute slots
            }
        }

        return $slots;
    }

    /**
     * Scope to get only available pitches.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}