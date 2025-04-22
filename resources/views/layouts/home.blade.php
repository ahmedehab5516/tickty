@extends('layouts.app')

@section('content')
<!-- Home Page Content -->

<!-- For all users (logged in or guests) -->
<section class="hero bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 fw-bold">Welcome to Tickty</h1>
        <p class="lead mb-4">Your one-stop solution for booking and managing movie tickets.</p>
        <p class="mb-4">Explore movies, choose your seats, and complete your payment in just a few clicks.</p>
        <div class="cta-buttons">
            <a href="{{ route('login') }}" class="btn btn-light btn-lg mx-2">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg mx-2">Register</a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features py-5">
    <div class="container text-center">
        <h2 class="mb-4">What We Do</h2>
        <div class="row">
            <div class="col-md-4">
                <h4>Browse Movies</h4>
                <p>Choose from a wide variety of movies and showtimes, including all the latest releases!</p>
            </div>
            <div class="col-md-4">
                <h4>Choose Your Seat</h4>
                <p>Select the perfect seat from our interactive seat map and enjoy your movie in comfort.</p>
            </div>
            <div class="col-md-4">
                <h4>Easy Payment</h4>
                <p>With a range of payment methods, booking your tickets is just a few clicks away.</p>
            </div>
        </div>
    </div>
</section>

@endsection
