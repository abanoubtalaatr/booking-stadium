<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Booking extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'pitch_id',
        'user_name',
        'user_email',
        'user_phone',
        'booking_date',
        'start_time',
        'end_time',
        'duration_minutes',
        'total_price',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'booking_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'total_price' => 'decimal:2',
        'duration_minutes' => 'integer',
    ];

    /**
     * Get the pitch that belongs to this booking.
     */
    public function pitch(): BelongsTo
    {
        return $this->belongsTo(Pitch::class);
    }

    /**
     * Get the stadium through the pitch relationship.
     */
    public function stadium()
    {
        return $this->pitch->stadium;
    }

    /**
     * Check if booking is active (not cancelled).
     */
    public function isActive()
    {
        return $this->status !== 'cancelled';
    }

    /**
     * Check if booking is in the past.
     */
    public function isPast()
    {
        $bookingDateTime = Carbon::parse($this->booking_date->format('Y-m-d') . ' ' . Carbon::parse($this->start_time)->format('H:i:s'));

        return $bookingDateTime->isPast();
    }

    /**
     * Check if booking can be cancelled.
     */
    public function canBeCancelled()
    {
        return !$this->isPast() && $this->status === 'confirmed';
    }

    /**
     * Scope to get active bookings.
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'cancelled');
    }

    /**
     * Scope to get bookings for today.
     */
    public function scopeToday($query)
    {
        return $query->where('booking_date', Carbon::today());
    }

    /**
     * Scope to get upcoming bookings.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('booking_date', '>=', Carbon::today());
    }
}
