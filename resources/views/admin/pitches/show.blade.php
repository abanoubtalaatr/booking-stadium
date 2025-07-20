@extends('layouts.app')

@section('title', 'Pitch Details - Admin')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="display-6">Pitch Details</h1>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.pitches.edit', $pitch) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Pitch
                        </a>
                        <a href="{{ route('admin.pitches.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-futbol me-2"></i>{{ $pitch->name }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">Basic Information</h6>
                                        <p><strong>Name:</strong> {{ $pitch->name }}</p>
                                        <p><strong>Stadium:</strong> {{ $pitch->stadium->name }}</p>
                                        <p><strong>Type:</strong> {{ ucfirst($pitch->type) }}</p>
                                        <p><strong>Surface:</strong> {{ ucfirst($pitch->surface) }}</p>
                                        <p><strong>Capacity:</strong> {{ $pitch->capacity }} players</p>
                                        <p><strong>Status:</strong> 
                                            @if($pitch->status === 'available')
                                                <span class="badge bg-success">Available</span>
                                            @elseif($pitch->status === 'maintenance')
                                                <span class="badge bg-warning">Maintenance</span>
                                            @else
                                                <span class="badge bg-danger">Unavailable</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">Slot Configuration</h6>
                                        <p><strong>Slot Type:</strong> 
                                            <span class="badge bg-primary">{{ $pitch->slot_type }} Minutes Only</span>
                                        </p>
                                        <p><strong>60-Min Rate:</strong> AED {{ number_format($pitch->hourly_rate_60, 2) }}</p>
                                        <p><strong>90-Min Rate:</strong> AED {{ number_format($pitch->hourly_rate_90, 2) }}</p>
                                        @if($pitch->slot_type === '60')
                                            <div class="alert alert-info">
                                                <i class="fas fa-clock me-2"></i>This pitch generates 60-minute slots only
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-clock me-2"></i>This pitch generates 90-minute slots only
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">Operating Hours</h6>
                                        <p><strong>Start Time:</strong> {{ $pitch->operating_start_time->format('H:i') }}</p>
                                        <p><strong>End Time:</strong> {{ $pitch->operating_end_time->format('H:i') }}</p>
                                        <p><strong>Operating Days:</strong></p>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($pitch->operating_days as $day)
                                                <span class="badge bg-secondary">{{ ucfirst($day) }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">Amenities</h6>
                                        @if($pitch->amenities && count($pitch->amenities) > 0)
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($pitch->amenities as $amenity)
                                                    <span class="badge bg-info">{{ $amenity }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted">No amenities listed</p>
                                        @endif
                                    </div>
                                </div>

                                @if($pitch->description)
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h6 class="fw-bold">Description</h6>
                                            <p>{{ $pitch->description }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-check me-2"></i>Today's Slots Preview
                                </h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $todaySlots = $pitch->getAvailableSlots(today()->format('Y-m-d'));
                                @endphp
                                @if(count($todaySlots) > 0)
                                    <p class="small text-muted mb-2">Available slots for {{ today()->format('M d, Y') }}:</p>
                                    @foreach(array_slice($todaySlots, 0, 6) as $slot)
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="small">{{ $slot['start_time'] }} - {{ $slot['end_time'] }}</span>
                                            <span class="badge bg-success small">AED {{ $slot['price'] }}</span>
                                        </div>
                                    @endforeach
                                    @if(count($todaySlots) > 6)
                                        <p class="small text-muted mb-0">... and {{ count($todaySlots) - 6 }} more slots</p>
                                    @endif
                                @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                        <p class="small">No available slots today</p>
                                        @if(!in_array(strtolower(today()->format('l')), $pitch->operating_days))
                                            <small>Pitch doesn't operate on {{ today()->format('l') }}s</small>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>Quick Stats
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="border-end">
                                                <div class="h5 text-primary">{{ count($todaySlots) }}</div>
                                                <small>Today's Slots</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="h5 text-success">{{ $pitch->slot_type }}'</div>
                                            <small>Slot Duration</small>
                                        </div>
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