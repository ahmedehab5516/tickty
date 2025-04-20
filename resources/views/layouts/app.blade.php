@php
    use Illuminate\Support\Facades\Auth;
    $authUser = Auth::user();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column" style="min-height: 100vh;">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        @if($authUser->role_id == 3)
            <a class="navbar-brand fw-bold" href="{{ route('superadmin.dashboard') }}">🎬 Tickty</a>
        @elseif($authUser->role_id == 2)
            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">🎬 Tickty</a>
        @else
            <a class="navbar-brand fw-bold" href="{{ route('user.dashboard') }}">🎬 Tickty</a>
        @endif

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            @if(Auth::check())
                <ul class="navbar-nav me-auto">
                
                    @if($authUser->role_id == 1)
                        <!-- Regular User -->
                        <li class="nav-item"><a class="nav-link" href="{{ route('user.movies.index') }}">Now Showing</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('user.bookings.index') }}">My Bookings</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('payments.index') }}">My Payments</a></li>
                    @endif

                    @if($authUser->role_id == 2)
                          <!-- Admin -->
                    <li class="nav-item"><a class="nav-link" href="{{ route('movies.index') }}">Browse Movies</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('showtimes.index') }}">All Showtimes</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('halls.index') }}">Manage Halls</a></li>
                    @endif

                    @if($authUser->role_id == 3)
                        <!-- Super Admin -->
                    <li class="nav-item"><a class="nav-link" href="{{ route('movies.index') }}">Browse Movies</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('showtimes.index') }}">All Showtimes</a></li>

                        <li class="nav-item"><a class="nav-link" href="{{ route('cinemas.index') }}">Cinemas</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('halls.index') }}">Halls</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('bookings.index') }}">All Bookings</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('payments.index') }}">All Payments</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('companies.index') }}">Companies</a></li>

                        <!-- Users Management -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="usersDropdown" role="button" data-bs-toggle="dropdown">
                                👥 Users
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('superadmin.manage_users.index') }}">View Users</a></li>
                                <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">Users Dashboard</a></li>
                            </ul>
                        </li>

                        <!-- Admin Management -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminsDropdown" role="button" data-bs-toggle="dropdown">
                                ⚙️ Admins
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('superadmin.manage_admins.create') }}">Create Admin</a></li>
                                <li><a class="dropdown-item" href="{{ route('superadmin.manage_admins.index') }}">View Admins</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>

                <!-- User Dropdown -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            {{ $authUser->name ?? 'User' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">My Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container py-4 mb-5">
    @yield('content')
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center text-lg-start mt-auto">
    <div class="container p-2">
        <p class="text-muted mb-0">© 2025 Tickty. All rights reserved.</p>
        <p><a href="#" class="text-white">Privacy Policy</a> | <a href="#" class="text-white">Terms of Service</a></p>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
