@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white">
            <h4 class="fw-bold mb-0">🪑 Choose a Seat for: {{ $movie->title }}</h4>
            <small class="text-light">Showtime: {{ \Carbon\Carbon::parse($showtime->start_time)->format('M d, H:i') }}</small>
        </div>

        <div class="card-body">
            <div class="text-center mb-4">
                <h5 class="mb-3">🟦 Seat Map</h5>
                <div class="d-flex justify-content-center mb-3">
                    <span class="badge bg-success me-2">Available</span>
                    <span class="badge bg-secondary me-2">Booked</span>
                    <span class="badge bg-warning text-dark">Selected</span>
                </div>
                <div class="alert alert-info" id="seat-selection-display">No seat selected.</div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered text-center align-middle">
                    <thead>
                        <tr>
                            <th>Row</th>
                            @for ($col = 1; $col <= $maxColumns; $col++)
                                <th>{{ $col }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupedSeats as $row => $seatsInRow)
                            <tr>
                                <td><strong>{{ $row }}</strong></td>
                                @for ($col = 1; $col <= $maxColumns; $col++)
                                    @php
                                        $seat = $seatsInRow->firstWhere('seat_column', $col);
                                    @endphp
                                    <td>
                                        @if ($seat)
                                            @php
                                                $isBooked = in_array($seat->id, $bookedSeatIds);
                                            @endphp
                                            <button type="button"
                                                class="btn btn-sm seat-btn {{ $isBooked ? 'unavailable bg-secondary text-white' : 'available btn-outline-success' }} {{ old('seat_id') == $seat->id ? 'selected' : '' }}"
                                                data-seat-id="{{ $seat->id }}"
                                                data-row="{{ $row }}"
                                                data-col="{{ $col }}"
                                                onclick="selectSeat(this)"
                                                {{ $isBooked ? 'disabled' : '' }}>
                                                {{ $col }}
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm seat-btn unavailable" disabled>X</button>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Dropdowns for Row and Column --}}
            <form action="{{ route('user.bookings.pay') }}" method="GET" id="seat-form">
                @csrf
                <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
                <input type="hidden" id="selected-seat-id" name="seat_id">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Seat Row</label>
                        <select class="form-select" id="seat-row-dropdown" onchange="highlightDropdownSeat()">
                            <option value="">-- Select Row --</option>
                            @foreach ($seatRows as $rowLabel)
                                <option value="{{ $rowLabel }}">{{ $rowLabel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Seat Column</label>
                        <select class="form-select" id="seat-col-dropdown" onchange="highlightDropdownSeat()">
                            <option value="">-- Select Column --</option>
                            @for ($col = 1; $col <= $maxColumns; $col++)
                                <option value="{{ $col }}">{{ $col }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg" id="confirm-btn" disabled>
                        ✅ Confirm & Proceed to Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function selectSeat(button) {
        // Remove old selection
        document.querySelectorAll('.seat-btn.selected').forEach(el => el.classList.remove('selected'));

        // Add new selection
        button.classList.add('selected');

        // Fill hidden input
        document.getElementById('selected-seat-id').value = button.dataset.seatId;

        // Display info
        document.getElementById('seat-selection-display').innerHTML =
            `✅ You selected: <strong>Row ${button.dataset.row}, Column ${button.dataset.col}</strong>`;

        // Enable submit
        document.getElementById('confirm-btn').disabled = false;

        // Update dropdowns
        document.getElementById('seat-row-dropdown').value = button.dataset.row;
        document.getElementById('seat-col-dropdown').value = button.dataset.col;
    }

    function highlightDropdownSeat() {
        const row = document.getElementById('seat-row-dropdown').value;
        const col = document.getElementById('seat-col-dropdown').value;

        if (row && col) {
            const button = document.querySelector(`.seat-btn[data-row="${row}"][data-col="${col}"]:not(.unavailable)`);

            if (button) {
                selectSeat(button);
            } else {
                alert('❌ This seat is unavailable.');
            }
        }
    }
</script>
@endsection
