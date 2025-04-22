@php use SimpleSoftwareIO\QrCode\Facades\QrCode; @endphp

@extends('layouts.app')

@section('content')
<div class="container mt-5">
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-header bg-gradient bg-success text-white rounded-top-4 py-3 d-flex justify-content-between align-items-center">
      <h4 class="fw-bold mb-0">🎟️ Your Ticket</h4>
      <a href="{{ route('user.bookings.index') }}" class="btn btn-light btn-sm rounded-pill">🧾 My Bookings</a>
    </div>

    <div class="card-body text-center bg-light rounded-bottom-4">

      <h5 class="mb-4 text-success">Thanks for booking, {{ auth()->user()->name }} 🎉</h5>

      <div id="ticket" class="ticket bg-white shadow-sm p-4 rounded-4 border mx-auto" style="max-width: 600px;">
        <h4 class="fw-bold text-primary mb-3">{{ $movie->title }}</h4>

        <ul class="list-unstyled mb-4 text-start">
          <li><strong>🎬 Showtime:</strong> {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('M d, H:i') }}</li>
          <li><strong>📍 Hall:</strong> {{ $booking->showtime->hall->name ?? 'N/A' }}</li>
          <li><strong>💺 Seats:</strong>
            @foreach($booking->seats as $seatId)
              <span class="badge bg-dark me-1">{{ $seatId }}</span>
            @endforeach
          </li>
          <li><strong>💳 Amount Paid:</strong> USD {{ number_format($payment->amount, 2) }}</li>
          <li><strong>🔢 Transaction ID:</strong> {{ $payment->transaction_id }}</li>
          <li><strong>🧾 Receipt:</strong> <a href="{{ $payment->stripe_receipt_url }}" target="_blank">View</a></li>
        </ul>

        <div class="qr-box border rounded-3 p-3 bg-light text-center mx-auto" style="max-width: 180px;">
          {!! QrCode::size(120)->generate(route('user.bookings.ticket', ['booking_id' => $booking->id])) !!}
          <p class="mt-2 small text-muted">Scan this QR at entry</p>
        </div>
      </div>

      <button onclick="downloadTicket()" class="btn btn-outline-primary mt-4 px-4 rounded-pill shadow-sm">⬇️ Download Ticket</button>

    </div>
  </div>
</div>
@endsection
