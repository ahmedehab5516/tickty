@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            @if($movie->poster_url)
                <img src="{{ $movie->poster_url }}" class="img-fluid rounded shadow-sm" alt="{{ $movie->title }} Poster">
            @endif
        </div>
        <div class="col-md-8">
            <h2>{{ $movie->title }}</h2>
            <p><strong>Genre:</strong> {{ $movie->genre }}</p>
            <p><strong>Duration:</strong> {{ $movie->duration_minutes }} minutes</p>
            <p><strong>Rating:</strong> {{ $movie->rating }}/5</p>
            <p>{{ $movie->description }}</p>

            @if($movie->trailer_url)
                <a href="{{ $movie->trailer_url }}" target="_blank" class="btn btn-sm btn-outline-primary">🎬 Watch Trailer</a>
            @endif

            <hr>
            <h5>Available Showtimes</h5>
            @forelse($movie->showtimes as $showtime)
                <div class="border rounded p-2 mb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ \Carbon\Carbon::parse($showtime->start_time)->format('M d, H:i') }}</strong>
                        <small class="text-muted d-block">{{ $showtime->language }} {{ $showtime->is_3d ? '| 3D' : '' }}</small>
                    </div>
                    <a href="{{ route('user.bookings.choose_seat', $movie->id) }}" class="btn btn-sm btn-success">Book</a>
                </div>
            @empty
                <p class="text-muted">No showtimes available.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
