@extends('layouts.app')

@section('title', 'Booking Confirmed - Stadium Booking')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body text-center p-5">
                        <!-- Success Icon -->
                        <div class="text-success mb-4">
                            <i class="fas fa-check-circle" style="font-size: 4rem;"></i>
                        </div>
                        
                        <!-- Success Message -->
                        <h1 class="display-5 fw-bold text-success mb-3">Booking Confirmed!</h1>
                        <p class="lead text-muted mb-4">
                            Your booking has been successfully confirmed. You will receive a confirmation email shortly.
                        </p>

                        <!-- Booking Details -->
                        <div class="card bg-light">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-ticket-alt me-2"></i>Booking Details
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-start">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">Booking Information</h6>
                                        <p class="mb-1"><strong>Booking ID:</strong> #{{ $booking->id }}</p>
                                        <p class="mb-1"><strong>Stadium:</strong> {{ $booking->pitch->stadium->name }}</p>
                                        <p class="mb-1"><strong>Pitch:</strong> {{ $booking->pitch->name }}</p>
                                        <p class="mb-1"><strong>Date:</strong> {{ $booking->booking_date->format('l, F d, Y') }}</p>
                                        <p class="mb-1"><strong>Time:</strong> {{ $booking->start_time }} - {{ $booking->end_time }}</p>
                                        <p class="mb-1"><strong>Duration:</strong> {{ $booking->duration_minutes }} minutes</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">Customer Information</h6>
                                        <p class="mb-1"><strong>Name:</strong> {{ $booking->user_name }}</p>
                                        <p class="mb-1"><strong>Email:</strong> {{ $booking->user_email }}</p>
                                        <p class="mb-1"><strong>Phone:</strong> {{ $booking->user_phone }}</p>
                                        <p class="mb-1"><strong>Total Paid:</strong> AED {{ number_format($booking->total_price, 2) }}</p>
                                        <p class="mb-1"><strong>Status:</strong> 
                                            <span class="badge bg-success">{{ ucfirst($booking->status) }}</span>
                                        </p>
                                    </div>
                                </div>
                                
                                @if($booking->notes)
                                    <hr>
                                    <h6 class="fw-bold">Special Notes</h6>
                                    <p class="mb-0">{{ $booking->notes }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Important Instructions -->
                        <div class="alert alert-info text-start mt-4">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-2"></i>Important Instructions
                            </h6>
                            <ul class="mb-0">
                                <li>Please arrive at least 10 minutes before your booking time</li>
                                <li>Bring a valid ID for verification</li>
                                <li>Contact the stadium directly if you need to make any changes</li>
                                <li>Free cancellation is available up to 2 hours before your booking</li>
                            </ul>
                        </div>

                        <!-- Stadium Contact Information -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-map-marker-alt me-2"></i>Stadium Contact Information
                                </h6>
                            </div>
                            <div class="card-body text-start">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="mb-1"><strong>Address:</strong> {{ $booking->pitch->stadium->address }}</p>
                                        @if($booking->pitch->stadium->phone)
                                            <p class="mb-1">
                                                <strong>Phone:</strong> 
                                                <a href="tel:{{ $booking->pitch->stadium->phone }}" class="text-decoration-none">
                                                    {{ $booking->pitch->stadium->phone }}
                                                </a>
                                            </p>
                                        @endif
                                        @if($booking->pitch->stadium->email)
                                            <p class="mb-0">
                                                <strong>Email:</strong> 
                                                <a href="mailto:{{ $booking->pitch->stadium->email }}" class="text-decoration-none">
                                                    {{ $booking->pitch->stadium->email }}
                                                </a>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        @if($booking->pitch->stadium->latitude && $booking->pitch->stadium->longitude)
                                            <a href="https://maps.google.com/?q={{ $booking->pitch->stadium->latitude }},{{ $booking->pitch->stadium->longitude }}" 
                                               target="_blank" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-map me-2"></i>View on Map
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-center mt-4">
                            <a href="{{ route('stadiums.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-home me-2"></i>Back to Home
                            </a>
                            <a href="{{ route('user.bookings') }}?email={{ $booking->user_email }}" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i>View My Bookings
                            </a>
                            <button onclick="window.print()" class="btn btn-outline-secondary">
                                <i class="fas fa-print me-2"></i>Print Confirmation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<style media="print">
    .btn, .alert, nav, footer { display: none !important; }
    .card { border: 1px solid #ddd !important; box-shadow: none !important; }
</style>
@endsection 