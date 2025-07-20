<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stadium extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'stadiums';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'address',
        'description',
        'capacity',
        'status',
        'latitude',
        'longitude',
        'phone',
        'email',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'capacity' => 'integer',
    ];

    /**
     * Get all pitches belonging to this stadium.
     */
    public function pitches(): HasMany
    {
        return $this->hasMany(Pitch::class);
    }

    /**
     * Get all bookings for this stadium through pitches.
     */
    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Pitch::class);
    }

    /**
     * Get only active pitches.
     */
    public function activePitches(): HasMany
    {
        return $this->pitches()->where('status', 'available');
    }

    /**
     * Scope to get only active stadiums.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
