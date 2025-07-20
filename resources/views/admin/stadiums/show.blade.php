@extends('layouts.app')

@section('title', 'Stadium Details - Admin')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="display-6">Stadium Details</h1>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.stadiums.edit', $stadium) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Stadium
                        </a>
                        <a href="{{ route('admin.stadiums.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-building me-2"></i>{{ $stadium->name }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">Basic Information</h6>
                                        <p><strong>Name:</strong> {{ $stadium->name }}</p>
                                        <p><strong>Address:</strong> {{ $stadium->address }}</p>
                                        <p><strong>Capacity:</strong> {{ number_format($stadium->capacity) }}</p>
                                        <p><strong>Status:</strong> 
                                            @if($stadium->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($stadium->status === 'maintenance')
                                                <span class="badge bg-warning">Maintenance</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">Contact Information</h6>
                                        <p><strong>Phone:</strong> {{ $stadium->phone ?: 'Not provided' }}</p>
                                        <p><strong>Email:</strong> {{ $stadium->email ?: 'Not provided' }}</p>
                                        @if($stadium->latitude && $stadium->longitude)
                                            <p><strong>Location:</strong> {{ $stadium->latitude }}, {{ $stadium->longitude }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if($stadium->description)
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h6 class="fw-bold">Description</h6>
                                            <p>{{ $stadium->description }}</p>
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
                                    <i class="fas fa-futbol me-2"></i>Pitches ({{ $stadium->pitches->count() }})
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($stadium->pitches->count() > 0)
                                    @foreach($stadium->pitches as $pitch)
                                        <div class="card mb-2">
                                            <div class="card-body p-3">
                                                <h6 class="card-title mb-1">{{ $pitch->name }}</h6>
                                                <small class="text-muted">{{ ucfirst($pitch->type) }} - {{ ucfirst($pitch->surface) }}</small>
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <small>AED {{ $pitch->hourly_rate_60 }}/{{ $pitch->hourly_rate_90 }}</small>
                                                    <span class="badge bg-{{ $pitch->status === 'available' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($pitch->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="text-center mt-3">
                                        <a href="{{ route('admin.pitches.create') }}?stadium_id={{ $stadium->id }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-2"></i>Add Pitch
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-futbol fa-2x mb-2"></i>
                                        <p>No pitches yet</p>
                                        <a href="{{ route('admin.pitches.create') }}?stadium_id={{ $stadium->id }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-2"></i>Add First Pitch
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 