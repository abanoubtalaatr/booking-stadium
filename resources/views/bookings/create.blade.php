@extends('layouts.app')

@section('title', 'Complete Booking - Stadium Booking')

@section('content')
<section class="py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('stadiums.index') }}">Home</a></li>
                <li class="breadcrumb-item active">Complete Booking</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-calendar-check me-2"></i>Complete Your Booking
                        </h4>
                    </div>
                    
                    <div class="card-body">
                        <!-- Booking Summary -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-2"></i>Booking Summary
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}<br>
                                    <strong>Time:</strong> {{ $startTime }} - {{ $endTime }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Duration:</strong> {{ $duration }} minutes<br>
                                    <strong>Price:</strong> AED {{ number_format($price, 2) }}
                                </div>
                            </div>
                        </div>

                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <h6 class="alert-heading">Please fix the following errors:</h6>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Booking Form -->
                        <form method="POST" action="{{ route('booking.store') }}">
                            @csrf
                            
                            <!-- Hidden Fields -->
                            <input type="hidden" name="pitch_id" value="{{ $pitchId }}">
                            <input type="hidden" name="booking_date" value="{{ $date }}">
                            <input type="hidden" name="start_time" value="{{ $startTime }}">
                            <input type="hidden" name="end_time" value="{{ $endTime }}">
                            <input type="hidden" name="duration_minutes" value="{{ $duration }}">

                            <div class="row g-3">
                                <!-- Personal Information -->
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <i class="fas fa-user me-2"></i>Personal Information
                                    </h5>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="user_name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('user_name') is-invalid @enderror" 
                                           id="user_name" name="user_name" 
                                           value="{{ old('user_name') }}" required>
                                    @error('user_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="user_email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('user_email') is-invalid @enderror" 
                                           id="user_email" name="user_email" 
                                           value="{{ old('user_email') }}" required>
                                    @error('user_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="user_phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control @error('user_phone') is-invalid @enderror" 
                                           id="user_phone" name="user_phone" 
                                           value="{{ old('user_phone') }}" 
                                           placeholder="+971501234567" required>
                                    @error('user_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Additional Information -->
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                                        <i class="fas fa-clipboard me-2"></i>Additional Information
                                    </h5>
                                </div>
                                
                                <div class="col-12">
                                    <label for="notes" class="form-label">Special Notes (Optional)</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3" 
                                              placeholder="Any special requirements or notes for your booking...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a> 
                                            and <a href="#" class="text-decoration-none">Cancellation Policy</a> *
                                        </label>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="col-12">
                                    <hr class="my-4">
                                    <div class="d-flex gap-3">
                                        <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Go Back
                                        </a>
                                        <button type="submit" class="btn btn-primary flex-fill">
                                            <i class="fas fa-check me-2"></i>Confirm Booking - AED {{ number_format($price, 2) }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Booking Guidelines -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-lightbulb me-2"></i>Booking Guidelines
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <strong>Arrival:</strong> Please arrive 10 minutes before your slot
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-id-card text-info me-2"></i>
                                <strong>ID Required:</strong> Bring a valid ID for verification
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-ban text-danger me-2"></i>
                                <strong>Cancellation:</strong> Free cancellation up to 2 hours before
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-shield-alt text-success me-2"></i>
                                <strong>Safety:</strong> Follow all stadium safety guidelines
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-tshirt text-warning me-2"></i>
                                <strong>Equipment:</strong> Bring your own sports gear
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-question-circle me-2"></i>Need Help?
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">Contact our support team:</p>
                        <p class="mb-1">
                            <i class="fas fa-phone me-2"></i>
                            <a href="tel:+971455501234" class="text-decoration-none">+971-4-555-0123</a>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-envelope me-2"></i>
                            <a href="mailto:support@stadiumbooking.com" class="text-decoration-none">support@stadiumbooking.com</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Auto-format phone number
    document.getElementById('user_phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.startsWith('971')) {
            value = '+' + value;
        } else if (value.startsWith('0')) {
            value = '+971' + value.substring(1);
        } else if (!value.startsWith('+')) {
            value = '+971' + value;
        }
        e.target.value = value;
    });
</script>
@endsection 