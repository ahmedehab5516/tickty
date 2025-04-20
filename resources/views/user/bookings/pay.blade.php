@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="fw-bold mb-0">💳 Payment Confirmation</h4>
                    <small>Movie: <strong>{{ $movie->title }}</strong> | Showtime: {{ \Carbon\Carbon::parse($showtime->start_time)->format('M d, H:i') }}</small>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('user.bookings.payment.process') }}" method="POST">
                        @csrf

                        <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                        <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
                        <input type="hidden" name="seat_id" value="{{ $seat->id }}">

                        <div class="mb-4">
                            <h6>🪑 Seat: Row {{ $seat->seat_row }}, Column {{ $seat->seat_column }} ({{ $seat->seat_type }})</h6>
                            <p><strong>Price:</strong> EGP {{ $price ?? '50.00' }}</p>
                        </div>

                        <div class="mb-3">
                            <label for="method" class="form-label">Select Payment Method</label>
                            <select name="method" id="method" class="form-select" required>
                                <option value="">-- Choose --</option>
                                <option value="cash">Cash</option>
                                <option value="card">Credit / Debit Card</option>
                                <option value="wallet">E-Wallet</option>
                            </select>
                        </div>

                        {{-- Additional info (if card selected) --}}
                        <div class="mb-3 d-none" id="card-info">
                            <label for="card_number" class="form-label">Card Number</label>
                            <input type="text" class="form-control" name="card_number" placeholder="XXXX-XXXX-XXXX-XXXX">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">✅ Complete Payment</button>
                        </div>
                    </form>

                    <hr>
                    <small class="text-muted">You will receive a confirmation once the payment is successful.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('method').addEventListener('change', function () {
        const cardInfo = document.getElementById('card-info');
        if (this.value === 'card') {
            cardInfo.classList.remove('d-none');
        } else {
            cardInfo.classList.add('d-none');
        }
    });
</script>
@endsection
