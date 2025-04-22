@extends('layouts.app')

@section('content')
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
          <h4 class="fw-bold mb-0">💳 Payment Confirmation</h4>
          <small>
            Movie: <strong>{{ $movie->title }}</strong> |
            Showtime: {{ \Carbon\Carbon::parse($showtime->start_time)->format('M d, H:i') }}
          </small>
        </div>

        <div class="card-body">
         @php
          $total = request('total_price') ?? 0;
        @endphp


          <form action="{{ route('user.bookings.stripe') }}" method="POST" id="stripe-form">
            @csrf

            {{-- Hidden Inputs --}}
            <input type="hidden" name="movie_id"    value="{{ $movie->id }}">
            <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
            <input type="hidden" name="seats" value='@json($seats->pluck("id"))'>
            <input type="hidden" name="stripeToken" id="stripe-token">

            {{-- Seat Summary --}}
            <div class="mb-4">
              <h6>🪑 Seats Chosen</h6>
              <ul class="list-unstyled">
                @foreach($seats as $seat)
                  <li>Row {{ $seat->seat_row }}, Col {{ $seat->seat_column }} ({{ ucfirst($seat->seat_type) }})</li>
                @endforeach
              </ul>
            <input type="hidden" name="price" id="final-price" value="{{ number_format($total, 2, '.', '') }}">

              {{-- Display Calculations --}}
         
              <p class="h5"><strong>Total:</strong> EGP {{ number_format($total, 2) }}</p>
            </div>

            {{-- Stripe Card Input --}}
            <div class="form-control mb-3" id="card-element"></div>

            {{-- Submit --}}
            <div class="d-grid pt-2">
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
<script src="https://js.stripe.com/v3/"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const stripe = Stripe('{{ env("STRIPE_KEY") }}');
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    const form = document.getElementById('stripe-form');
    const tokenInput = document.getElementById('stripe-token');

    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent default until token is created

        form.querySelector('button[type="submit"]').disabled = true;

        stripe.createToken(card).then(function (result) {
            if (result.error) {
                alert(result.error.message);
                form.querySelector('button[type="submit"]').disabled = false;
            } else {
                tokenInput.value = result.token.id;
                form.submit(); // Submit form after token is added
            }
        });
    });
});
</script>
@endsection
