<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SeatController;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;


// Redirect the root URL to the login page
Route::get('/', function () {
    return redirect()->route('login');
});


    // Resource Routes (Only accessible when logged in)
    Route::resources([
        'movies' => MovieController::class,
        'cinemas' => CinemaController::class,
        'halls' => HallController::class,
        'roles' => RoleController::class,
        'showtimes' => ShowtimeController::class,
        'seats' => SeatController::class,
        'bookings' => BookingController::class,
        'companies' => CompanyController::class,
        'payments' => PaymentController::class,
    ]);

Route::get('/halls/{hall}/seats', [HallController::class, 'viewSeats'])->name('halls.view_seats');
Route::put('/seats/update-bulk/{hall}', [SeatController::class, 'updateBulk'])->name('seats.update_bulk');


// Registration Route
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Login Route
Route::get('/login', [LoginController::class, 'showloginform'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Logout Route
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');



Route::middleware(['auth', 'issuperadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    ////
    Route::get('/superadmin/create_admin', [SuperAdminController::class, 'createAdminForm'])->name('superadmin.manage_admins.create');
    Route::post('/superadmin/create_admin', [SuperAdminController::class, 'storeAdmin'])->name('superadmin.store_admin');
    Route::get('/superadmin/view_admins', [SuperAdminController::class, 'viewAdmins'])->name('superadmin.manage_admins.index');
    ////
    Route::get('/superadmin/view_users', [SuperAdminController::class, 'viewUsers'])->name('superadmin.manage_users.index');
    ////
    Route::get('/superadmin/payments', [PaymentController::class, 'index'])->name('payments.index');
    ////
    Route::get('/superadmin/bookings', [BookingController::class, 'index'])->name('bookings.index');



});




Route::middleware(['auth', 'isadmin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

Route::middleware(['auth', 'isuser'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/user/bookings', [UserController::class, 'bookingIndex'])->name('user.bookings.index');
    Route::get('/user/bookings/choose_seat', [UserController::class, 'chooseSeat'])->name('user.bookings.choose_seat');
    Route::get('/user/bookings/payment', [UserController::class, 'pay'])->name('user.bookings.pay');
    Route::post('/user/bookings/store', [UserController::class, 'storeBooking'])->name('user.bookings.store');

    ////
    Route::get('/user/movies', [UserController::class, 'moviesIndex'])->name('user.movies.index');
    Route::get('/user/movies/movie_details/{movie}', [UserController::class, 'showMovie'])->name('user.movies.show');

});
