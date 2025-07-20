@extends('layouts.app')

@section('title', 'My Bookings - Stadium Booking')

@section('content')
<section class="py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-calendar-check me-3"></i>My Bookings
                </h1>
                <p class="lead text-muted">View and manage your stadium bookings</p>
            </div>
        </div>

        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('user.bookings') }}">
                            <div class="input-group">
                                <input type="email" class="form-control" name="email" 
                                       placeholder="Enter your email address" 
                                       value="{{ $email }}" required>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search me-2"></i>Search Bookings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bookings Results -->
        @if($email)
            @if($bookings->count() > 0)
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>Bookings for {{ $email }}</h4>
                            <span class="badge bg-primary">{{ $bookings->count() }} booking(s) found</span>
                        </div>
                        
                        @foreach($bookings as $booking)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-lg-8">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5 class="card-title mb-2">
                                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                        {{ $booking->pitch->stadium->name }}
                                                    </h5>
                                                    <p class="mb-1">
                                                        <strong>Pitch:</strong> {{ $booking->pitch->name }}
                                                    </p>
                                                    <p class="mb-1">
                                                        <strong>Date:</strong> {{ $booking->booking_date->format('l, F d, Y') }}
                                                    </p>
                                                    <p class="mb-1">
                                                        <strong>Time:</strong> {{ $booking->start_time }} - {{ $booking->end_time }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-1">
                                                        <strong>Duration:</strong> {{ $booking->duration_minutes }} minutes
                                                    </p>
                                                    <p class="mb-1">
                                                        <strong>Total:</strong> AED {{ number_format($booking->total_price, 2) }}
                                                    </p>
                                                    <p class="mb-1">
                                                        <strong>Booking ID:</strong> #{{ $booking->id }}
                                                    </p>
                                                    <p class="mb-1">
                                                        <strong>Status:</strong> 
                                                        @if($booking->status === 'confirmed')
                                                            <span class="badge bg-success">Confirmed</span>
                                                        @elseif($booking->status === 'cancelled')
                                                            <span class="badge bg-danger">Cancelled</span>
                                                        @elseif($booking->status === 'completed')
                                                            <span class="badge bg-info">Completed</span>
                                                        @else
                                                            <span class="badge bg-warning">{{ ucfirst($booking->status) }}</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            @if($booking->notes)
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        <strong>Notes:</strong> {{ $booking->notes }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="col-lg-4 text-lg-end">
                                            <div class="d-flex flex-column gap-2">
                                                @if($booking->pitch->stadium->phone)
                                                    <a href="tel:{{ $booking->pitch->stadium->phone }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-phone me-2"></i>Call Stadium
                                                    </a>
                                                @endif
                                                
                                                @if($booking->pitch->stadium->latitude && $booking->pitch->stadium->longitude)
                                                    <a href="https://maps.google.com/?q={{ $booking->pitch->stadium->latitude }},{{ $booking->pitch->stadium->longitude }}" 
                                                       target="_blank" class="btn btn-outline-info btn-sm">
                                                        <i class="fas fa-map me-2"></i>Directions
                                                    </a>
                                                @endif
                                                
                                            </div>
                                            
                                            <small class="text-muted mt-2">
                                                @if($booking->isPast())
                                                    <i class="fas fa-clock me-1"></i>Past booking
                                                @else
                                                    <i class="fas fa-calendar me-1"></i>Upcoming
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                                <h4>No bookings found</h4>
                                <p class="text-muted mb-4">
                                    No bookings were found for the email address "{{ $email }}". 
                                    Make sure you entered the correct email address.
                                </p>
                                <a href="{{ route('stadiums.index') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Make a New Booking
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <!-- Instructions when no email entered -->
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-search fa-4x text-muted mb-3"></i>
                            <h4>Find Your Bookings</h4>
                            <p class="text-muted mb-4">
                                Enter your email address above to view all your current and past bookings. 
                                You can manage, cancel, or get directions for your upcoming bookings.
                            </p>
                            
                            <div class="row g-3 text-start">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-search fa-2x text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-1">Search</h6>
                                            <small class="text-muted">Enter your email to find bookings</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-eye fa-2x text-success me-3"></i>
                                        <div>
                                            <h6 class="mb-1">View Details</h6>
                                            <small class="text-muted">See all booking information</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-cog fa-2x text-info me-3"></i>
                                        <div>
                                            <h6 class="mb-1">Manage</h6>
                                            <small class="text-muted">Cancel or get directions</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection 