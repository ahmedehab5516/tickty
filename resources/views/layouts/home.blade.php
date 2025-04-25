@extends('layouts.app')

@section('content')
<!-- Home Page Content -->

    

<!-- For all users (logged in or guests) -->
<section class="hero   text-center py-5">

    <div class="container">
        <h1 class="display-4 fw-bold">Welcome to Tickty</h1>
        <p class="lead mb-4">Your one-stop solution for booking and managing movie tickets.</p>
        <p class="mb-4">Explore movies, choose your seats, and complete your payment in just a few clicks.</p>
        <div class="cta-buttons">
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg mx-2">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg mx-2">Register</a>
        </div>
    </div>
</section>





<!-- Features Section -->
<!-- Features Section -->
<section class="features ">
    <div class="container text-center">
        <h2 class="mb-5 display-4 fw-bold section-title ">What We Do</h2>
        
        <!-- First Feature -->
        <div class="row align-items-center mb-5">
            <div class="col-md-6">
                <img src="{{ asset('images/karen-zhao-jLRIsfkWRGo-unsplash.jpg') }}" class=" img-fluid rounded  shadow-sm feature-img  " alt="Feature 1 Image">
            </div>
            <div class="col-md-6 img-desc">
                <h4 class="fw-bold">Cinema's Magic</h4>
                <p>In the dark, the screen comes alive,  
                    A world of stories, where dreams thrive.  
                    Lights, camera, action—magic in the air,  
                    Every frame a tale, beyond compare.  

                    Actors breathe life, emotions unfold,  
                    In every scene, a story is told.  
                    Cinema’s glow, a world to explore,  
                    In every movie, we crave for more.</p>
            </div>
        </div>

        <!-- Second Feature -->

        <div class="row align-items-center mb-5 ">
            <div class="col-md-6 order-md-2">
                <img  src="{{asset('images/3d-rendering-cinema-director-chair.jpg')}}"  class="img-fluid rounded shadow-lg feature-img" style="max-width: 300px;" alt="Feature 2 Image">
            </div>
            <div class="col-md-6 img-desc">
                <h4 class="fw-bold ">Find Your Perfect Spot</h4>
                <p >Unlock a personalized experience—choose your ideal seat with our interactive map. Comfort and convenience await you for an unforgettable movie night.</p>
           
            </div>
        </div>


        <!-- Third Feature -->
   
            <div class="row align-items-center mb-5">
                <div class="col-md-6">
                    <img src="{{asset('images/ticket-2974645_1920.jpg')}}"  class="img-fluid rounded shadow-lg feature-img" alt="Feature 3 Image">
                </div>
                <div class="col-md-6 img-desc">
                    <h4 class="fw-bold">Seamless Payment Experience</h4>
                    <p >Choose from a variety of secure payment methods—booking your tickets has never been easier. Just a few clicks, and you're ready to enjoy the show.</p>
                </div>
            </div>

    </div>
</section>




@endsection
