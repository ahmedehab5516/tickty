<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Auth\LoginController,
    Auth\LogoutController,
    Auth\RegisterController,
    AdminController,
    BookingController,
    CinemaController,
    CompanyController,
    HallController,
    MovieController,
    PaymentController,
    RoleController,
    SeatController,
    ShowtimeController,
    SuperAdminController,
    UserController
};

// Homepage
Route::get('/', fn() => view('layouts.home'))->name('home');

// Authentication
Route::get('/login', [LoginController::class, 'showloginform'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Global Resource Routes (protected)
Route::middleware(['auth'])->group(function () {
    Route::resources([
        'movies'    => MovieController::class,
        'cinemas'   => CinemaController::class,
        'halls'     => HallController::class,
        'roles'     => RoleController::class,
        'showtimes' => ShowtimeController::class,
        'seats'     => SeatController::class,
        'bookings'  => BookingController::class,
        'companies' => CompanyController::class,
        'payments'  => PaymentController::class,
    ]);

    Route::get('/halls/{hall}/seats', [HallController::class, 'viewSeats'])->name('halls.view_seats');
    Route::put('/seats/update-bulk/{hall}', [SeatController::class, 'updateBulk'])->name('seats.update_bulk');
});

// Super Admin Routes
Route::middleware(['auth', 'issuperadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    Route::get('/superadmin/create_admin', [SuperAdminController::class, 'createAdminForm'])->name('superadmin.manage_admins.create');
    Route::post('/superadmin/create_admin', [SuperAdminController::class, 'storeAdmin'])->name('superadmin.store_admin');
    Route::get('/superadmin/view_admins', [SuperAdminController::class, 'viewAdmins'])->name('superadmin.manage_admins.index');
    Route::get('/superadmin/view_users', [SuperAdminController::class, 'viewUsers'])->name('superadmin.manage_users.index');
    Route::get('/superadmin/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/superadmin/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/superadmin/users/profile/{id}', [UserController::class, 'viewProfile'])->name('superadmin.users.profile');
});

// Admin Routes
Route::middleware(['auth', 'isadmin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

// User Routes
Route::middleware(['auth', 'isuser'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/user/bookings', [UserController::class, 'bookingIndex'])->name('user.bookings.index');
    Route::get('/user/bookings/choose_seat/{movieId}/{showtimeId}', [UserController::class, 'chooseSeat'])->name('user.bookings.choose_seat');
    Route::get('/user/bookings/payment', [UserController::class, 'pay'])->name('user.bookings.pay');
    Route::post('/user/bookings/stripe-payment', [UserController::class, 'Stripe'])->name('user.bookings.stripe');
    Route::get('/user/bookings/ticket', [UserController::class, 'Ticket'])->name('user.bookings.ticket');

    Route::get('/user/movies', [UserController::class, 'moviesIndex'])->name('user.movies.index');
    Route::get('/user/movies/movie_details/{movie}', [UserController::class, 'showMovie'])->name('user.movies.show');

    // Profile page (for user themselves)
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
});