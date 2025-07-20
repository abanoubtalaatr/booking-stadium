@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="display-6">
                            <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                        </h1>
                        <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}! Here's your system overview.</p>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">Last updated: {{ now()->format('M d, Y H:i') }}</small>
                    </div>
                </div>

                <!-- Quick Stats Cards -->
                <div class="row g-4 mb-5">
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-building fa-2x mb-3"></i>
                                <h3 class="mb-1">{{ $stats['total_stadiums'] }}</h3>
                                <p class="mb-2">Total Stadiums</p>
                                <small>{{ $stats['active_stadiums'] }} Active</small>
                            </div>
                            <div class="card-footer bg-white bg-opacity-25">
                                <a href="{{ route('admin.stadiums.index') }}" class="text-white text-decoration-none">
                                    <i class="fas fa-eye me-2"></i>View All
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-futbol fa-2x mb-3"></i>
                                <h3 class="mb-1">{{ $stats['total_pitches'] }}</h3>
                                <p class="mb-2">Total Pitches</p>
                                <small>{{ $stats['available_pitches'] }} Available</small>
                            </div>
                            <div class="card-footer bg-white bg-opacity-25">
                                <a href="{{ route('admin.pitches.index') }}" class="text-white text-decoration-none">
                                    <i class="fas fa-eye me-2"></i>View All
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-info text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-check fa-2x mb-3"></i>
                                <h3 class="mb-1">{{ $stats['total_bookings'] }}</h3>
                                <p class="mb-2">Total Bookings</p>
                                <small>{{ $stats['today_bookings'] }} Today</small>
                            </div>
                            <div class="card-footer bg-white bg-opacity-25">
                                <a href="{{ route('admin.bookings.index') }}" class="text-white text-decoration-none">
                                    <i class="fas fa-eye me-2"></i>View All
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-warning text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-dollar-sign fa-2x mb-3"></i>
                                <h3 class="mb-1">{{ number_format($stats['total_revenue'], 0) }}</h3>
                                <p class="mb-2">Total Revenue (AED)</p>
                                <small>{{ $stats['active_bookings'] }} Active Bookings</small>
                            </div>
                            <div class="card-footer bg-white bg-opacity-25">
                                <span class="text-white">
                                    <i class="fas fa-chart-line me-2"></i>Revenue Overview
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row g-4 mb-5">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-plus-circle me-2"></i>Quick Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <a href="{{ route('admin.stadiums.create') }}" class="btn btn-primary w-100">
                                            <i class="fas fa-building me-2"></i>Add Stadium
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('admin.pitches.create') }}" class="btn btn-success w-100">
                                            <i class="fas fa-futbol me-2"></i>Add Pitch
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('admin.stadiums.index') }}" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-list me-2"></i>Manage Stadiums
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('admin.pitches.index') }}" class="btn btn-outline-success w-100">
                                            <i class="fas fa-list me-2"></i>Manage Pitches
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-clock me-2"></i>Recent Bookings
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($recent_bookings->count() > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($recent_bookings as $booking)
                                            <div class="list-group-item px-0">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1">{{ $booking->user_name }}</h6>
                                                        <p class="mb-1 small">{{ $booking->pitch->stadium->name }} - {{ $booking->pitch->name }}</p>
                                                        <small class="text-muted">{{ $booking->booking_date->format('M d, Y') }} at {{ $booking->start_time }}</small>
                                                    </div>
                                                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">
                                            View All Bookings
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                        <p>No recent bookings</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Status -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>System Status
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <div class="border-end">
                                            <div class="h4 text-success">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <p class="mb-0">System Online</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border-end">
                                            <div class="h4 text-primary">{{ $stats['active_stadiums'] }}/{{ $stats['total_stadiums'] }}</div>
                                            <p class="mb-0">Active Stadiums</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border-end">
                                            <div class="h4 text-success">{{ $stats['available_pitches'] }}/{{ $stats['total_pitches'] }}</div>
                                            <p class="mb-0">Available Pitches</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="h4 text-info">{{ $stats['today_bookings'] }}</div>
                                        <p class="mb-0">Today's Bookings</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 