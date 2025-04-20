@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="fw-bold mb-0">My Bookings</h4>
        </div>

        <div class="card-body">
            @if($bookings->isEmpty())
                <div class="alert alert-info">You haven't booked any tickets yet.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Movie</th>
                                <th>Hall</th>
                                <th>Seat</th>
                                <th>Showtime</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $index => $booking)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                               <td>
    {{ optional($booking->showtime)->start_time ? \Carbon\Carbon::parse($booking->showtime->start_time)->format('Y-m-d H:i') : 'N/A' }}
</td>

                                    <td>Row {{ $booking->seat->seat_row ?? '?' }}, Col {{ $booking->seat->seat_column ?? '?' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
