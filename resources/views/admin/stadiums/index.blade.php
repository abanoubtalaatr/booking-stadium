@extends('layouts.app')

@section('title', 'Admin - Manage Stadiums')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="display-6">
                        <i class="fas fa-building me-2"></i>Manage Stadiums
                    </h1>
                    <a href="{{ route('admin.stadiums.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Stadium
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
                                <h4>{{ $stadiums->total() }}</h4>
                                <p class="mb-0">Total Stadiums</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4>{{ $stadiums->where('status', 'active')->count() }}</h4>
                                <p class="mb-0">Active</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h4>{{ $stadiums->where('status', 'maintenance')->count() }}</h4>
                                <p class="mb-0">Maintenance</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h4>{{ $stadiums->sum(function($stadium) { return $stadium->pitches->count(); }) }}</h4>
                                <p class="mb-0">Total Pitches</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-body">
                        @if($stadiums->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Stadium Name</th>
                                            <th>Location</th>
                                            <th>Capacity</th>
                                            <th>Pitches</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stadiums as $stadium)
                                            <tr>
                                                <td>{{ $stadium->id }}</td>
                                                <td>
                                                    <strong>{{ $stadium->name }}</strong>
                                                    @if($stadium->email)
                                                        <br><small class="text-muted">{{ $stadium->email }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ Str::limit($stadium->address, 40) }}
                                                    @if($stadium->phone)
                                                        <br><small class="text-muted">{{ $stadium->phone }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($stadium->capacity) }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $stadium->pitches->count() }} pitches</span>
                                                </td>
                                                <td>
                                                    @if($stadium->status === 'active')
                                                        <span class="badge bg-success">Active</span>
                                                    @elseif($stadium->status === 'maintenance')
                                                        <span class="badge bg-warning">Maintenance</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.stadiums.show', $stadium) }}" 
                                                           class="btn btn-outline-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.stadiums.edit', $stadium) }}" 
                                                           class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="POST" action="{{ route('admin.stadiums.destroy', $stadium) }}" 
                                                              style="display: inline;"
                                                              onsubmit="return confirm('Are you sure you want to delete this stadium? This will also delete all associated pitches.')">
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
                                {{ $stadiums->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-building fa-4x text-muted mb-3"></i>
                                <h4>No Stadiums Found</h4>
                                <p class="text-muted mb-4">Start by creating your first stadium.</p>
                                <a href="{{ route('admin.stadiums.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Create First Stadium
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