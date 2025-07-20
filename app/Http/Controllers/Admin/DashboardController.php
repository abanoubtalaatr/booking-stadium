<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stadium;
use App\Models\Pitch;
use App\Models\Booking;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get overview statistics
        $stats = [
            'total_stadiums' => Stadium::count(),
            'active_stadiums' => Stadium::where('status', 'active')->count(),
            'total_pitches' => Pitch::count(),
            'available_pitches' => Pitch::where('status', 'available')->count(),
            'total_bookings' => Booking::count(),
            'today_bookings' => Booking::whereDate('booking_date', today())->count(),
            'active_bookings' => Booking::where('status', 'confirmed')->count(),
            'total_revenue' => Booking::where('status', 'confirmed')->sum('total_price'),
        ];

        // Get recent bookings
        $recent_bookings = Booking::with(['pitch.stadium'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_bookings'));
    }
}
