@extends('layouts.app')

@section('title', 'Admin - Manage Pitches')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="display-6">
                        <i class="fas fa-cog me-2"></i>Manage Pitches
                    </h1>
                    <a href="{{ route('admin.pitches.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Pitch
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif


                <!-- Quick Stats -->
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h4>{{ $pitches->total() }}</h4>
                                <p class="mb-0">Total Pitches</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4>{{ $pitches->where('status', 'available')->count() }}</h4>
                                <p class="mb-0">Available</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h4>{{ $pitches->where('status', 'maintenance')->count() }}</h4>
                                <p class="mb-0">Maintenance</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h4>{{ $pitches->where('status', 'unavailable')->count() }}</h4>
                                <p class="mb-0">Unavailable</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-body">
                        @if($pitches->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Stadium</th>
                                            <th>Pitch Name</th>
                                            <th>Type</th>
                                            <th>Surface</th>
                                            <th>Operating Hours</th>
                                            <th>Rates (60/90 min)</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pitches as $pitch)
                                            <tr>
                                                <td>{{ $pitch->id }}</td>
                                                <td>{{ $pitch->stadium->name ?? 'N/A' }}</td>
                                                <td>
                                                    <strong>{{ $pitch->name }}</strong>
                                                    <br><small class="text-muted">{{ $pitch->stadium->name }}</small>
                                                    <br><span class="badge bg-{{ $pitch->slot_type === '60' ? 'primary' : 'warning' }}">{{ $pitch->slot_type }} min slots</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ ucfirst($pitch->type) }}</span>
                                                </td>
                                                <td>{{ ucfirst($pitch->surface) }}</td>
                                                <td>
                                                    <small>
                                                        {{ \Carbon\Carbon::parse($pitch->operating_start_time)->format('H:i') }} - 
                                                        {{ \Carbon\Carbon::parse($pitch->operating_end_time)->format('H:i') }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <small>
                                                        AED {{ $pitch->hourly_rate_60 }} / AED {{ $pitch->hourly_rate_90 }}
                                                    </small>
                                                </td>
                                                <td>
                                                    @if($pitch->status === 'available')
                                                        <span class="badge bg-success">Available</span>
                                                    @elseif($pitch->status === 'maintenance')
                                                        <span class="badge bg-warning">Maintenance</span>
                                                    @else
                                                        <span class="badge bg-danger">Unavailable</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.pitches.show', $pitch) }}" 
                                                           class="btn btn-outline-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.pitches.edit', $pitch) }}" 
                                                           class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="POST" action="{{ route('admin.pitches.destroy', $pitch) }}" 
                                                              style="display: inline;"
                                                              onsubmit="return confirm('Are you sure you want to delete this pitch?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $pitches->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-futbol fa-4x text-muted mb-3"></i>
                                <h4>No Pitches Found</h4>
                                <p class="text-muted mb-4">Start by creating your first pitch.</p>
                                <a href="{{ route('admin.pitches.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Create First Pitch
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection 