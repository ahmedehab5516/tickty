@extends('layouts.app')

@section('title', 'Staff Dashboard')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-4">
                <h1 class="fw-bold">Welcome to Your Dashboard</h1>
                <p class="text-muted">Here, you can manage your tasks, check your shifts, and monitor hall assignments.</p>
            </div>

            <!-- Dashboard Content -->
            <div class="mb-5">
                <h4 class="fw-semibold">Assigned Tasks</h4>
                <p>Here you'll see your daily tasks and responsibilities.</p>
                <button class="btn btn-outline-primary">View Tasks</button> {{-- You can link this to a task management page later --}}
            </div>

            <div class="mb-5">
                <h4 class="fw-semibold">Hall Readiness</h4>
                <p>Ensure that your assigned hall is ready for the next screening.</p>
                <button class="btn btn-outline-secondary">Check Hall Status</button> {{-- Link to hall management --}}
            </div>

        </div>
    </div>
</div>
@endsection
