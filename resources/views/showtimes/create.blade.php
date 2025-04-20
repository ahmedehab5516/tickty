@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="fw-bold mb-0">Create New Showtime</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('showtimes.store') }}" method="POST">
                @csrf

                <!-- Movie -->
                <div class="mb-3">
                    <label for="movie_id" class="form-label">Movie</label>
                    <select name="movie_id" id="movie_id" class="form-select" required>
                        <option value="">-- Select Movie --</option>
                        @foreach ($movies as $movie)
                            <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Hall -->
                <div class="mb-3">
                    <label for="hall_id" class="form-label">Hall</label>
                    <select name="hall_id" id="hall_id" class="form-select" required>
                        <option value="">-- Select Hall --</option>
                        @foreach ($halls as $hall)
                            <option value="{{ $hall->id }}">{{ $hall->name }} ({{ $hall->cinema->name ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                </div>
                   <!-- language -->
               <!-- Language -->
                <div class="mb-3">
                    <label for="language" class="form-label">Language</label>
                    <select name="language" id="language" class="form-select" required>
                        <option value="">-- Select Language --</option>
                        <option value="English" {{ old('language', $showtime->language ?? '') == 'English' ? 'selected' : '' }}>English</option>
                        <option value="Arabic" {{ old('language', $showtime->language ?? '') == 'Arabic' ? 'selected' : '' }}>Arabic</option>
                        <option value="French" {{ old('language', $showtime->language ?? '') == 'French' ? 'selected' : '' }}>French</option>
                        <option value="Hindi" {{ old('language', $showtime->language ?? '') == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                        <option value="Other" {{ old('language', $showtime->language ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>



                <!-- Start Time -->
                <div class="mb-3">
                    <label for="start_time" class="form-label">Start Time</label>
                    <input type="datetime-local" name="start_time" id="start_time" class="form-control" required>
                </div>

                <!-- End Time -->
                <div class="mb-3">
                    <label for="end_time" class="form-label">End Time</label>
                    <input type="datetime-local" name="end_time" id="end_time" class="form-control" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Create Showtime</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const startInput = document.getElementById('start_time');
        const endInput = document.getElementById('end_time');

        form.addEventListener('submit', function (e) {
            const startTime = new Date(startInput.value);
            const endTime = new Date(endInput.value);

            if (endTime <= startTime) {
                e.preventDefault();
                alert('End time must be after start time.');
                endInput.focus();
            }
        });
    });
</script>
@endsection
