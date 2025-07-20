@extends('layouts.app')

@section('title', $stadium->name . ' - Stadium Booking')

@section('content')
<section class="py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('stadiums.index') }}">Home</a></li>
                <li class="breadcrumb-item active">{{ $stadium->name }}</li>
            </ol>
        </nav>

        <!-- Stadium Header -->
        <div class="row mb-5">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">{{ $stadium->name }}</h1>
                <p class="lead text-muted mb-3">
                    <i class="fas fa-location-dot me-2"></i>
                    {{ $stadium->address }}
                </p>
                <p class="mb-4">{{ $stadium->description }}</p>
                
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="border-end">
                            <div class="h4 text-primary fw-bold">{{ $stadium->capacity }}</div>
                            <small class="text-muted">Total Capacity</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <div class="h4 text-success fw-bold">{{ $stadium->pitches->count() }}</div>
                            <small class="text-muted">Available Pitches</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <div class="h4 text-info fw-bold">6AM-11PM</div>
                            <small class="text-muted">Operating Hours</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="h4 text-warning fw-bold">
                            @if($stadium->phone)
                                <a href="tel:{{ $stadium->phone }}" class="text-decoration-none">
                                    <i class="fas fa-phone"></i>
                                </a>
                            @else
                                <i class="fas fa-envelope"></i>
                            @endif
                        </div>
                        <small class="text-muted">Contact</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>Select Date
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('stadiums.show', $stadium) }}">
                            <div class="mb-3">
                                <input type="date" name="date" class="form-control" 
                                       value="{{ $date }}" min="{{ date('Y-m-d') }}"
                                       onchange="this.form.submit()">
                            </div>
                        </form>
                        
                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-info-circle me-2"></i>
                                Showing slots for {{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Slots -->
        <div class="row">
            <div class="col-12">
                <h3 class="mb-4">
                    <i class="fas fa-clock me-2"></i>Available Time Slots
                </h3>
                
                @if(count($availableSlots) > 0)
                    @foreach($availableSlots as $pitchData)
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h5 class="mb-0">
                                            <i class="fas fa-futbol me-2"></i>
                                            {{ $pitchData['pitch_name'] }}
                                        </h5>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <span class="badge bg-light text-dark me-2">
                                            {{ ucfirst($pitchData['pitch_surface']) }} Surface
                                        </span>
                                        <span class="badge bg-success">
                                            {{ ucfirst($pitchData['pitch_type']) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                @if(count($pitchData['available_slots']) > 0)
                                    <div class="row g-2">
                                        @foreach($pitchData['available_slots'] as $slot)
                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                <a href="{{ route('booking.form') }}?pitch_id={{ $pitchData['pitch_id'] }}&date={{ $date }}&start_time={{ $slot['start_time'] }}&end_time={{ $slot['end_time'] }}&duration={{ $slot['duration_minutes'] }}&price={{ $slot['price'] }}" 
                                                   class="btn btn-outline-primary slot-button w-100">
                                                    <div class="fw-bold">{{ $slot['start_time'] }} - {{ $slot['end_time'] }}</div>
                                                    <small>{{ $slot['duration_minutes'] }}min - AED {{ $slot['price'] }}</small>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">No available slots for this pitch on selected date</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h4>No available slots</h4>
                            <p class="text-muted mb-4">
                                No time slots are available for {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}. 
                                Please try a different date.
                            </p>
                            <a href="{{ route('stadiums.show', $stadium) }}?date={{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" 
                               class="btn btn-primary">
                                <i class="fas fa-arrow-right me-2"></i>Try Tomorrow
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Stadium Information -->
        <div class="row mt-5">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Stadium Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Facilities</h6>
                                <ul class="list-unstyled">
                                    @foreach($stadium->pitches->first()->amenities ?? [] as $amenity)
                                        <li><i class="fas fa-check text-success me-2"></i>{{ ucwords(str_replace('_', ' ', $amenity)) }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Contact Information</h6>
                                @if($stadium->phone)
                                    <p><i class="fas fa-phone me-2"></i>{{ $stadium->phone }}</p>
                                @endif
                                @if($stadium->email)
                                    <p><i class="fas fa-envelope me-2"></i>{{ $stadium->email }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>Operating Hours
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Monday - Sunday:</span>
                            <span class="fw-bold">6:00 AM - 11:00 PM</span>
                        </div>
                        <hr>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Bookings available in 60 and 90-minute slots
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 