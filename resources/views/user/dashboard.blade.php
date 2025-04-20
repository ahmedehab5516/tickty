@extends('layouts.app')

@section('content')
    @php
        use Illuminate\Support\Facades\Auth;
        $user = Auth::user();
    @endphp

    <div class="mt-4">
        <h1>Welcome, {{ $user->name ?? 'Guest' }}!</h1>
      
        <p class="lead">Here you can view your bookings, check showtimes, and manage your account.</p>

        <!-- Example cards or info -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">My Bookings</div>
                    <div class="card-body">
                        <p class="card-text">View all your cinema bookings and history.</p>
                        <a href="{{ route('user.bookings.index') }}" class="btn btn-light btn-sm">Go</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Upcoming Showtimes</div>
                    <div class="card-body">
                        <p class="card-text">Browse and book upcoming movie showtimes.</p>
                        <a href="{{ route('showtimes.index') }}" class="btn btn-light btn-sm">View</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
