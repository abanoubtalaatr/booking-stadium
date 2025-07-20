@extends('layouts.app')

@section('title', 'Admin - All Bookings')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="display-6">
                        <i class="fas fa-calendar-check me-2"></i>All Bookings
                    </h1>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-filter me-2"></i>Filter Bookings
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.bookings.index') }}">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" 
                                           value="{{ request('date') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">All Statuses</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search me-2"></i>Filter
                                    </button>
                                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-refresh me-2"></i>Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Bookings Table -->
                <div class="card">
                    <div class="card-body">
                        @if($bookings->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Customer</th>
                                            <th>Stadium & Pitch</th>
                                            <th>Date & Time</th>
                                            <th>Duration</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings as $booking)
                                            <tr>
                                                <td>
                                                    <strong>#{{ $booking->id }}</strong>
                                                    <br><small class="text-muted">{{ $booking->created_at->format('M d, Y') }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $booking->user_name }}</strong>
                                                    <br><small class="text-muted">{{ $booking->user_email }}</small>
                                                    <br><small class="text-muted">{{ $booking->user_phone }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $booking->pitch->stadium->name }}</strong>
                                                    <br><small class="text-muted">{{ $booking->pitch->name }}</small>
                                                    <br><small class="badge bg-info">{{ ucfirst($booking->pitch->type) }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $booking->booking_date->format('M d, Y') }}</strong>
                                                    <br><small class="text-muted">{{ $booking->start_time }} - {{ $booking->end_time }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $booking->duration_minutes }} min</span>
                                                </td>
                                                <td>
                                                    <strong>AED {{ number_format($booking->total_price, 2) }}</strong>
                                                </td>
                                                <td>
                                                    @if($booking->status === 'confirmed')
                                                        <span class="badge bg-success">Confirmed</span>
                                                    @elseif($booking->status === 'cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @elseif($booking->status === 'completed')
                                                        <span class="badge bg-info">Completed</span>
                                                    @else
                                                        <span class="badge bg-warning">{{ ucfirst($booking->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-info btn-sm" 
                                                                onclick="showBookingDetails({{ json_encode($booking->load(['pitch.stadium'])) }})">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        @if($booking->status === 'confirmed' && !$booking->isPast())
                                                            <button class="btn btn-outline-warning btn-sm"
                                                                    onclick="cancelBooking({{ $booking->id }})">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $bookings->appends(request()->query())->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                                <h4>No Bookings Found</h4>
                                <p class="text-muted mb-4">
                                    @if(request()->hasAny(['date', 'status']))
                                        No bookings match your filter criteria. Try adjusting your filters.
                                    @else
                                        No bookings have been made yet.
                                    @endif
                                </p>
                                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-refresh me-2"></i>Clear Filters
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Statistics -->
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h4>{{ $bookings->total() }}</h4>
                                <p class="mb-0">Total Results</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4>{{ $bookings->where('status', 'confirmed')->count() }}</h4>
                                <p class="mb-0">Confirmed</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h4>{{ $bookings->where('status', 'pending')->count() }}</h4>
                                <p class="mb-0">Pending</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h4>{{ $bookings->where('status', 'cancelled')->count() }}</h4>
                                <p class="mb-0">Cancelled</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-check me-2"></i>Booking Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bookingDetailsContent">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showBookingDetails(booking) {
        const content = document.getElementById('bookingDetailsContent');
        content.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-bold">Customer Information</h6>
                    <p><strong>Name:</strong> ${booking.user_name}</p>
                    <p><strong>Email:</strong> ${booking.user_email}</p>
                    <p><strong>Phone:</strong> ${booking.user_phone}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold">Booking Information</h6>
                    <p><strong>Booking ID:</strong> #${booking.id}</p>
                    <p><strong>Date:</strong> ${new Date(booking.booking_date).toLocaleDateString()}</p>
                    <p><strong>Time:</strong> ${booking.start_time} - ${booking.end_time}</p>
                    <p><strong>Duration:</strong> ${booking.duration_minutes} minutes</p>
                    <p><strong>Price:</strong> AED ${parseFloat(booking.total_price).toFixed(2)}</p>
                    <p><strong>Status:</strong> <span class="badge bg-${booking.status === 'confirmed' ? 'success' : booking.status === 'cancelled' ? 'danger' : 'warning'}">${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}</span></p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="fw-bold">Stadium & Pitch Information</h6>
                    <p><strong>Stadium:</strong> ${booking.pitch.stadium.name}</p>
                    <p><strong>Pitch:</strong> ${booking.pitch.name}</p>
                    <p><strong>Type:</strong> ${booking.pitch.type.charAt(0).toUpperCase() + booking.pitch.type.slice(1)}</p>
                    <p><strong>Surface:</strong> ${booking.pitch.surface.charAt(0).toUpperCase() + booking.pitch.surface.slice(1)}</p>
                </div>
            </div>
            ${booking.notes ? `
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="fw-bold">Notes</h6>
                        <p>${booking.notes}</p>
                    </div>
                </div>
            ` : ''}
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
        modal.show();
    }

    async function cancelBooking(bookingId) {
        if (!confirm('Are you sure you want to cancel this booking?')) {
            return;
        }

        try {
            const response = await fetch(`/api/bookings/${bookingId}/cancel`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                alert('Booking cancelled successfully');
                location.reload();
            } else {
                alert('Error cancelling booking: ' + data.message);
            }
        } catch (error) {
            alert('Error cancelling booking: ' + error.message);
        }
    }
</script>
@endsection 