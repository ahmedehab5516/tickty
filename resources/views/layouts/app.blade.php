@php
    use Illuminate\Support\Facades\Auth;
    $authUser = Auth::user(); // Get the authenticated user
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tickty')</title> <!-- Default title is 'Tickty' -->
     <!-- Custom Page Styles -->
    <link rel="stylesheet" href="{{ url('css/movie_details.css') }}">
    <link rel="stylesheet" href="{{ url('css/payment.css') }}">
    <link rel="stylesheet" href="{{ url('css/user_profile.css') }}">
    <link rel="stylesheet" href="{{ url('css/user_movies_index.css') }}">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ url('css/main.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

 
  
</head>
<body class="d-flex flex-column" style="min-height: 100vh;">
  


<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        @if(Auth::check())
            @php $roleId = $authUser->role_id; @endphp

            {{-- Brand Link Based on Role --}}
            @if($roleId == 3)
                <a class="navbar-brand fw-bold" href="{{ route('superadmin.dashboard') }}">🎬 Tickty</a>
            @elseif($roleId == 2)
                <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">🎬 Tickty</a>
            @elseif($roleId == 4)
                <a class="navbar-brand fw-bold" href="{{ route('staff.dashboard') }}">🎬 Tickty</a> {{-- You may create this --}}
            @else
                <a class="navbar-brand fw-bold" href="{{ route('user.dashboard') }}">🎬 Tickty</a>
            @endif
        @else
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">🎬 Tickty</a>
        @endif

        <!-- Navbar toggler for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ms-auto">

                     

                   
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('user.movies.index') }}">Now Showing</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('help') }}">Help</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('contactus') }}">Contact Us</a></li>
                    @endguest

                    <!-- Links for Authenticated Users -->
                    @if(Auth::check())
                        @if($roleId == 1) {{-- Regular User --}}
                            <li class="nav-item"><a class="nav-link" href="{{ route('user.bookings.index') }}">My Bookings</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">My Wallet</a></li> {{-- TO DO --}}
                              <li class="nav-item"><a class="nav-link" href="{{ route('user.movies.index') }}">Now Showing</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('help') }}">Help</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('contactus') }}">Contact Us</a></li>
                        @endif

                    {{-- STAFF ROLE --}}
                    @if($roleId == 4)
                        <li class="nav-item"><a class="nav-link" href="#">Assigned Tasks</a></li> {{-- TO DO --}}
                        <li class="nav-item"><a class="nav-link" href="#">Today's Showtimes</a></li> {{-- TO DO --}}
                    @endif

                    {{-- ADMIN ROLE --}}
                    @if($roleId == 2)
                        <li class="nav-item"><a class="nav-link" href="{{ route('movies.index') }}">Manage Movies</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('showtimes.index') }}">Manage Showtimes</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('halls.index') }}">Manage Halls</a></li>
                       <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="manageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Manage Staff
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="manageDropdown">
                                <!-- Create Staff Link -->
                                <li class="nav-item"><a class="dropdown-item" href="{{ route('admin.manage_staff.viewStaff') }}">View Staff</a></li>
                               
                                
                                <!-- Staff Assignment Link -->
                                <li class="nav-item"><a class="dropdown-item" href="{{ route('admin.manage_staff.assignTaskForm') }}">Staff Assignment</a></li>
                            </ul>
                        </li>


                   
                    @endif

                    {{-- SUPERADMIN ROLE --}}
                    @if($roleId == 3)
   
                                <li class="nav-item"><a class="nav-link" href="#">Subscription Settings</a></li> {{-- TO DO --}}
                                <li class="nav-item"><a class="nav-link" href="#">Reports</a></li> {{-- TO DO --}}
                                <li class="nav-item"><a class="nav-link" href="{{route ('superadmin.manage_admins.index')}}">Manage Admins</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('cinemas.index') }}">Cinemas</a></li>
                               
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="manageDropdown" role="button" data-bs-toggle="dropdown">
                                Manage
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('movies.index') }}">Browse Movies</a></li>
                                <li><a class="dropdown-item" href="{{ route('showtimes.index') }}">All Showtimes</a></li>
                                <li><a class="dropdown-item" href="{{ route('halls.index') }}">Manage Halls</a></li>
                             
                                <li><a class="dropdown-item" href="{{ route('bookings.index') }}">All Bookings</a></li>
                                <li><a class="dropdown-item" href="{{ route('payments.index') }}">All Payments</a></li>
                                
                              
                            </ul>
                        </li>





                    @endif
                @endif
        
            </ul>

            {{-- USER DROPDOWN --}}
            @if(Auth::check())
                @php
                    $roleId = $authUser->role_id;
                @endphp

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown">
                            {{ $authUser->name ?? 'User' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <!-- Common to all roles -->
                            
                            <!-- Role-based Dashboard Links -->
                            @if($roleId == 1) {{-- Regular User --}}
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}">Profile</a></li>
                            @elseif($roleId == 2) {{-- Admin --}}
                                <li><a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a></li>
                            @elseif($roleId == 3) {{-- Super Admin --}}
                                <li><a class="dropdown-item" href="{{ route('superadmin.profile') }}">Profile</a></li>
                            @elseif($roleId == 4) {{-- Staff --}}
                                <li><a class="dropdown-item" href="{{ route('staff.profile') }}">Profile</a></li>
                            @endif

                            <li><hr class="dropdown-divider"></li>

                            <!-- Logout -->
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            @endif

        </div>
    </div>
</nav>


@if(request()->routeIs('home') || request()->routeIs('login'))
    <div id="backgroundImage"></div>
@endif




<!-- Main Content -->
<div class="container  mb-5 ">
    @yield('content')
</div>

<!-- Footer -->
<footer class=" text-white text-center text-lg-start mt-auto">
    <div class="container">
        <!-- Copyright Section -->
        <div class="row mb-4">
            <div class="col-12">
                <p class="text-muted mb-0">© 2025 Tickty. All rights reserved.</p>
            </div>
        </div>

        <!-- Legal Links Section -->
        <div class="row mb-4">
            <div class="col-12">
                <p>
                    <a href="#" class="text-white">Privacy Policy</a> | 
                    <a href="#" class="text-white">Terms of Service</a>
                </p>
            </div>
        </div>

        <!-- Social Media Links Section -->
        <div class="row mb-4">
            <div class="col-12">
                <p class="mb-0">Follow Us</p>
                <div class="social-icons">
                    <a href="#" class="fab fa-facebook-f text-white me-3"></a>
                    <a href="#" class="fab fa-twitter text-white me-3"></a>
                    <a href="#" class="fab fa-instagram text-white me-3"></a>
                    <a href="#" class="fab fa-linkedin-in text-white"></a>
                </div>
            </div>
        </div>

        <!-- Contact Information Section -->
        <div class="row">
            <div class="col-12">
                <p>Contact Us: <a href="mailto:support@tickty.com" class="text-white">support@tickty.com</a></p>
                <p>Phone: +1 (800) 123-4567</p>
            </div>
        </div>
    </div>
</footer>


<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

// JavaScript to add the 'scrolled' class when the user scrolls
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 100) {
        navbar.classList.add('scrolled'); // Add dark background when scrolled
    } else {
        navbar.classList.remove('scrolled'); // Remove dark background when at top
    }
});


document.addEventListener('DOMContentLoaded', function () {
    const backgroundDiv = document.getElementById('backgroundImage');
    let imageIndex = 0;
    const tmdbApiKey = "{{ env('TMDB_KEY') }}"; // Embedding the API key
    
    // Function to fetch movie backdrop images
    async function fetchImages() {
        try {
            const response = await fetch(`https://api.themoviedb.org/3/movie/popular?api_key=${tmdbApiKey}&language=en-US&page=1`);
            const data = await response.json();
            return data.results.map(movie => `https://image.tmdb.org/t/p/original${movie.backdrop_path}`);
        } catch (error) {
            console.error('Error fetching images:', error);
            return [];
        }
    }

    // Function to change the background image and animate the position
    async function changeBackgroundImage() {
        const images = await fetchImages();
        
        if (images.length > 0) {
            // Update the background image of the section
            backgroundDiv.style.backgroundImage = `url(${images[imageIndex]})`;

            // Set the background position to create a sliding effect from left to right
            backgroundDiv.style.backgroundPosition = '100% center'; // Start position (right)
            setTimeout(() => {
                backgroundDiv.style.backgroundPosition = '0% center'; // End position (left)
            }, 50); // Small delay to ensure the transition happens

            // Increment the index and loop back to 0 when it reaches the last image
            imageIndex = (imageIndex + 1) % images.length;
        }
    }

    // Call the changeBackgroundImage function every 5 seconds
    setInterval(changeBackgroundImage, 5000);

    // Initial background image load
    changeBackgroundImage();
});
</script>




<script>
document.addEventListener('DOMContentLoaded', function () {
    const navbar = document.querySelector('.navbar .container');
    const authUser = "{{ Auth::user() }}"; // Get the authenticated user

    // Create the Login and Register buttons
    const loginButton = `<a href="{{ route('login') }}" class="btn btn-outline-light btn-sm mx-2">Login</a>`;
    const registerButton = `<a href="{{ route('register') }}" class="btn btn-outline-light btn-sm mx-2">Register</a>`;

    // Function to handle scroll event
    window.addEventListener('scroll', function() {
        // Check if the user has scrolled down
        if (window.scrollY > 100 && !authUser) { // Only show the buttons if the user is not logged in
            // If the user has scrolled down, add the login and register buttons
            if (!navbar.querySelector('.btn-outline-light')) {
                navbar.innerHTML += loginButton + registerButton;
            }
        } else {
            // Remove the buttons when the user scrolls back to the top
            if (navbar.querySelector('.btn-outline-light')) {
                const buttons = navbar.querySelectorAll('.btn-outline-light');
                buttons.forEach(button => button.remove());
            }
        }
    });
});
</script>



<script>
function updateFileName() {
    var fileInput = document.getElementById('profile_image');
    var fileNameDisplay = document.getElementById('fileName');
    var fileName = fileInput.files[0] ? fileInput.files[0].name : 'No file chosen';
    fileNameDisplay.value = fileName; // Update the file name display

    // Handle image preview
    var file = fileInput.files[0];
    var reader = new FileReader();
    
    if (file) {
        reader.readAsDataURL(file); // Read the file as a data URL (this will be used for the image preview)
    }
}

function updateFileName() {
    var fileInput = document.getElementById('profile_image');
    var fileNameDisplay = document.getElementById('fileName');
    var fileName = fileInput.files[0] ? fileInput.files[0].name : 'No file chosen';
    fileNameDisplay.value = fileName; // Update the file name display

    // Handle image preview
    var file = fileInput.files[0];
    var reader = new FileReader();
    
    if (file) {
        reader.readAsDataURL(file); // Read the file as a data URL (this will be used for the image preview)
    }
}
</script>


@yield('scripts')
</body>
</html>



