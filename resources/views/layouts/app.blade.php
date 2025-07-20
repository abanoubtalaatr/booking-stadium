<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Stadium Booking System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
        }
        .stadium-card {
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .stadium-card:hover {
            transform: translateY(-5px);
        }
        .pitch-card {
            border-left: 4px solid #28a745;
        }
        .slot-button {
            margin: 2px;
            min-width: 120px;
        }
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            transition: left 0.3s ease;
            z-index: 1040;
            color: white;
            overflow-y: auto;
        }
        
        .sidebar.show {
            left: 0;
        }
        
        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-menu {
            padding: 0;
            list-style: none;
            margin: 0;
        }
        
        .sidebar-menu li a {
            display: block;
            padding: 0.75rem 1rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: #3498db;
        }
        
        .sidebar-menu li a i {
            width: 20px;
            margin-right: 10px;
        }
        
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1030;
            display: none;
        }
        
        .sidebar-overlay.show {
            display: block;
        }
        
        .main-content {
            transition: margin-left 0.3s ease;
        }
        
        .main-content.sidebar-open {
            margin-left: 250px;
        }
        
        @media (max-width: 768px) {
            .main-content.sidebar-open {
                margin-left: 0;
            }
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    @if(auth()->check() && session('is_admin'))
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h5 class="mb-0">
                <i class="fas fa-cog me-2"></i>Admin Panel
            </h5>
            <small class="text-muted">Welcome, {{ auth()->user()->name }}</small>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('stadiums.index') }}" class="{{ request()->routeIs('stadiums.*') && !request()->routeIs('admin.*') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>Public Home
                </a>
            </li>
            <li>
                <a href="{{ route('admin.stadiums.index') }}" class="{{ request()->routeIs('admin.stadiums.index') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>All Stadiums
                </a>
            </li>
            <li>
                <a href="{{ route('admin.pitches.index') }}" class="{{ request()->routeIs('admin.pitches.index') ? 'active' : '' }}">
                    <i class="fas fa-futbol"></i>All Pitches
                </a>
            </li>
            <li>
                <a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings.index') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>All Bookings
                </a>
            </li>
            <li class="mt-3">
                <a href="{{ route('admin.stadiums.create') }}">
                    <i class="fas fa-plus"></i>Add Stadium
                </a>
            </li>
            <li>
                <a href="{{ route('admin.pitches.create') }}">
                    <i class="fas fa-plus"></i>Add Pitch
                </a>
            </li>
            <li class="mt-3">
                <a href="#" onclick="toggleSidebar()">
                    <i class="fas fa-times"></i>Close Menu
                </a>
            </li>
        </ul>
    </div>
    @endif

    <!-- Sidebar Overlay -->
    @if(auth()->check() && session('is_admin'))
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    @endif

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <button class="sidebar-toggle me-3" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                
                <a class="navbar-brand" href="{{ route('stadiums.index') }}">
                    <i class="fas fa-futbol"></i> Stadium Booking
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('stadiums.index') }}">
                                <i class="fas fa-home"></i> Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.bookings') }}">
                                <i class="fas fa-calendar-check"></i> My Bookings
                            </a>
                        </li>
                        <li class="nav-item">
                            @if(auth()->check() && session('is_admin'))
                                <a class="nav-link" href="#" onclick="toggleSidebar()">
                                    <i class="fas fa-cog"></i> Admin Panel
                                </a>
                            @else
                                <a class="nav-link" href="{{ route('admin.login') }}">
                                    <i class="fas fa-cog"></i> Admin Login
                                </a>
                            @endif
                        </li>
                        @if(auth()->check() && session('is_admin'))
                            <li class="nav-item">
                                <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="nav-link btn btn-link" style="border: none; background: none;">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Stadium Booking System</h5>
                        <p>Book your favorite football pitches with ease and convenience.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h6>Contact</h6>
                        <p>
                            <i class="fas fa-envelope"></i> support@stadiumbooking.com<br>
                            <i class="fas fa-phone"></i> +971-4-555-0123
                        </p>
                    </div>
                </div>
                <hr class="my-3">
                <div class="text-center">
                    <p>&copy; {{ date('Y') }} Stadium Booking System. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bookings Modal -->
    <div class="modal fade" id="bookingsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-check me-2"></i>View Bookings
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="bookingsDate" class="form-label">Select Date</label>
                            <input type="date" class="form-control" id="bookingsDate" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button class="btn btn-primary" onclick="loadBookings()">
                                <i class="fas fa-search me-2"></i>Load Bookings
                            </button>
                        </div>
                    </div>
                    <div id="bookingsContent" class="mt-3">
                        <div class="text-center text-muted">
                            <i class="fas fa-calendar fa-2x mb-2"></i>
                            <p>Select a date and click "Load Bookings" to view bookings for that day.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            @if(auth()->check() && session('is_admin'))
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            
            if (sidebar && overlay && mainContent) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                
                if (window.innerWidth > 768) {
                    mainContent.classList.toggle('sidebar-open');
                }
            }
            @else
            // Redirect to admin login if not authenticated
            window.location.href = '{{ route("admin.login") }}';
            @endif
        }

        @if(auth()->check() && session('is_admin'))
        // Admin-only functions
        function showBookingsModal() {
            const modal = new bootstrap.Modal(document.getElementById('bookingsModal'));
            modal.show();
        }

        async function loadBookings() {
            const date = document.getElementById('bookingsDate').value;
            const content = document.getElementById('bookingsContent');
            
            if (!date) {
                alert('Please select a date');
                return;
            }

            content.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading bookings...</p>
                </div>
            `;

            try {
                const response = await fetch(`/api/bookings?date=${date}`);
                const data = await response.json();

                if (data.success && data.data.length > 0) {
                    content.innerHTML = `
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Pitch</th>
                                        <th>Time</th>
                                        <th>Duration</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.data.map(booking => `
                                        <tr>
                                            <td>#${booking.id}</td>
                                            <td>
                                                <strong>${booking.user_name}</strong><br>
                                                <small>${booking.user_email}</small>
                                            </td>
                                            <td>
                                                ${booking.pitch.stadium.name}<br>
                                                <small>${booking.pitch.name}</small>
                                            </td>
                                            <td>${booking.start_time} - ${booking.end_time}</td>
                                            <td>${booking.duration_minutes} min</td>
                                            <td>AED ${booking.total_price}</td>
                                            <td>
                                                <span class="badge bg-${booking.status === 'confirmed' ? 'success' : booking.status === 'cancelled' ? 'danger' : 'warning'}">
                                                    ${booking.status}
                                                </span>
                                            </td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    `;
                } else {
                    content.innerHTML = `
                        <div class="text-center text-muted">
                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                            <p>No bookings found for ${date}</p>
                        </div>
                    `;
                }
            } catch (error) {
                content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error loading bookings: ${error.message}
                    </div>
                `;
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = event.target.closest('.sidebar-toggle');
            
            if (sidebar && window.innerWidth <= 768 && sidebar.classList.contains('show') && !sidebar.contains(event.target) && !sidebarToggle) {
                toggleSidebar();
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            
            if (sidebar && overlay && mainContent) {
                if (window.innerWidth <= 768) {
                    mainContent.classList.remove('sidebar-open');
                } else if (sidebar.classList.contains('show')) {
                    mainContent.classList.add('sidebar-open');
                }
            }
        });
        @endif
    </script>
    @yield('scripts')
</body>
</html> 