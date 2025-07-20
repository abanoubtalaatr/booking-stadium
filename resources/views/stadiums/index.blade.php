@extends('layouts.app')

@section('title', 'Stadium Booking - Home')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Book Your Perfect Pitch</h1>
                <p class="lead mb-4">Discover and book premium football stadiums and pitches in your area. Easy booking, competitive rates, world-class facilities.</p>
                <div class="d-flex gap-3">
                    <div class="text-center">
                        <div class="h3 fw-bold">{{ $stadiums->count() }}</div>
                        <small>Stadiums</small>
                    </div>
                    <div class="text-center">
                        <div class="h3 fw-bold">{{ $stadiums->sum(function($stadium) { return $stadium->pitches->count(); }) }}</div>
                        <small>Pitches</small>
                    </div>
                    <div class="text-center">
                        <div class="h3 fw-bold">24/7</div>
                        <small>Support</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-futbol" style="font-size: 12rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Stadiums Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-5 fw-bold mb-3">Available Stadiums</h2>
                <p class="lead text-muted">Choose from our premium collection of football stadiums and pitches</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($stadiums as $stadium)
                <div class="col-lg-6">
                    <div class="card stadium-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title fw-bold">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    {{ $stadium->name }}
                                </h5>
                                <span class="badge bg-success">{{ $stadium->pitches->count() }} Pitches</span>
                            </div>
                            
                            <p class="text-muted mb-3">
                                <i class="fas fa-location-dot me-2"></i>
                                {{ $stadium->address }}
                            </p>
                            
                            <p class="card-text">{{ $stadium->description }}</p>
                            
                            <div class="row text-center mb-4">
                                <div class="col-4">
                                    <div class="border-end">
                                        <div class="fw-bold text-primary">{{ $stadium->capacity }}</div>
                                        <small class="text-muted">Capacity</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <div class="fw-bold text-success">{{ $stadium->pitches->count() }}</div>
                                        <small class="text-muted">Pitches</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold text-info">
                                        AED {{ number_format($stadium->pitches->min('hourly_rate_60'), 0) }}+
                                    </div>
                                    <small class="text-muted">Per Hour</small>
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                @foreach($stadium->pitches->take(3) as $pitch)
                                    <div class="col-md-4">
                                        <div class="card pitch-card">
                                            <div class="card-body p-2">
                                                <h6 class="card-title mb-1">{{ $pitch->name }}</h6>
                                                <small class="text-muted">{{ ucfirst($pitch->surface) }}</small>
                                                <div class="small mt-1">
                                                    <strong>AED {{ $pitch->hourly_rate_60 }}</strong> /60min
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="d-flex gap-2">
                                <a href="{{ route('stadiums.show', $stadium) }}" class="btn btn-primary flex-fill">
                                    <i class="fas fa-calendar-plus me-2"></i>Book Now
                                </a>
                                @if($stadium->phone)
                                    <a href="tel:{{ $stadium->phone }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4>No stadiums available</h4>
                        <p class="text-muted">Please check back later for available stadiums.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="mb-3">
                    <i class="fas fa-clock fa-3x text-primary"></i>
                </div>
                <h5>Flexible Timing</h5>
                <p class="text-muted">60 and 90-minute slots available from 6 AM to 11 PM</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="mb-3">
                    <i class="fas fa-shield-alt fa-3x text-success"></i>
                </div>
                <h5>Secure Booking</h5>
                <p class="text-muted">Safe and secure booking system with instant confirmation</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="mb-3">
                    <i class="fas fa-star fa-3x text-warning"></i>
                </div>
                <h5>Premium Facilities</h5>
                <p class="text-muted">World-class pitches with modern amenities and equipment</p>
            </div>
        </div>
    </div>
</section>
@endsection 