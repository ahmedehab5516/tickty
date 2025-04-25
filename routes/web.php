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
    StaffController,
    SuperAdminController,
    UserController,
    Controller
};

// Homepage
Route::get('/', fn() => view('layouts.home'))->name('home');

Route::get('/About', fn() => view('layouts.about'))->name('about');
Route::get('/Contact-Us', fn() => view('layouts.contactus'))->name('contactus');
Route::get('/Help', fn() => view('layouts.help'))->name('help');

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


//////////////////////////!

// Super Admin Routes
Route::middleware(['auth', 'issuperadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    
    // Dashboard Route
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');

    //manage admin
    Route::get('/create-admin', [SuperAdminController::class, 'createAdminForm'])->name('manage_admins.create');
    Route::post('/create-admin', [SuperAdminController::class, 'storeAdmin'])->name('store_admin');
    Route::get('/view-admins', [SuperAdminController::class, 'viewAdmins'])->name('manage_admins.index');
    Route::get('/edit-admin/{id}', [SuperAdminController::class, 'editAdmin'])->name('manage_admins.edit'); // Pass admin ID
    Route::post('/update-admin/{id}', [SuperAdminController::class, 'updateAdmin'])->name('manage_admins.update'); // Pass admin ID
    Route::delete('/delete-admin/{id}', [SuperAdminController::class, 'deleteAdmin'])->name('manage_admins.delete'); // Pass admin ID

    // User Management Routes
    Route::get('/view-users', [SuperAdminController::class, 'viewUsers'])->name('manage_users.index');  // View All Users
    Route::get('/users/profile/{id}', [UserController::class, 'viewProfile'])->name('users.profile');  // View Specific User Profile

    // Payments and Bookings Management Routes
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');  // View All Payments
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');  // View All Bookings

    // Super Admin Profile Routes
    Route::get('/profile', [SuperAdminController::class, 'profile'])->name('profile');  // View Super Admin Profile
    Route::put('/profile/{id}', [SuperAdminController::class, 'updateProfile'])->name('update_profile'); // Make sure the name is 'admin.update_profile'

});



//////////////////////////!

Route::middleware(['auth', 'isadmin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Create Staff Routes
    Route::get('/view-staff', [AdminController::class, 'viewStaff'])->name('manage_staff.viewStaff');
    Route::get('/create-staff', [AdminController::class, 'createStaffForm'])->name('manage_staff.createStaffForm');
    Route::post('/store-staff', [AdminController::class, 'store'])->name('storeStaff');
    
    // Edit Staff Routes
    Route::get('/edit-staff/{id}', [AdminController::class, 'edit'])->name('manage_staff.edit');
    Route::post('/update-staff/{id}', [AdminController::class, 'update'])->name('manage_staff.updateStaff');
    
    // Delete Staff Routes
    Route::delete('/delete-staff/{id}', [AdminController::class, 'delete'])->name('manage_staff.delete');

    // Task Assignment Routes
    Route::get('/manage-staff/assign-task', [AdminController::class, 'assignTaskForm'])->name('manage_staff.assignTaskForm');
    Route::post('/manage-staff/assign-task', [AdminController::class, 'assignTask'])->name('manage_staff.assignTask');
    
// Route to view assignments of a staff member
    Route::get('/admin/staff/{staff_id}/assignments', [AdminController::class, 'viewAssignments'])->name('manage_staff.viewAssignments');
  



    Route::get('/profile', [AdminController::class, 'profile'])->name('profile'); // Corrected route definition for profile
    Route::put('/profile/{id}', [AdminController::class, 'updateProfile'])->name('update_profile'); // Make sure the name is 'admin.update_profile'


});




Route::middleware(['auth', 'isstaff'])->group(function () {
    Route::get('/staff/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
    Route::post('/staff/complete-task/{id}', [StaffController::class, 'completeTask'])->name('staff.completeTask');

    Route::get('/staff/profile', [StaffController::class, 'profile'])->name('staff.profile'); // Display profile
    Route::put('/staff/profile/{id}', [StaffController::class, 'updateProfile'])->name('staff.update_profile'); // Update profile

});




    Route::get('/user/movies', [UserController::class, 'moviesIndex'])->name('user.movies.index');
// User Routes
Route::middleware(['auth', 'isuser'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/user/bookings', [UserController::class, 'bookingIndex'])->name('user.bookings.index');
    Route::get('/user/bookings/choose_seat/{movieId}/{showtimeId}', [UserController::class, 'chooseSeat'])->name('user.bookings.choose_seat');
    Route::get('/user/bookings/payment', [UserController::class, 'pay'])->name('user.bookings.pay');
    Route::post('/user/bookings/stripe-payment', [UserController::class, 'Stripe'])->name('user.bookings.stripe');
    Route::get('/user/bookings/ticket', [UserController::class, 'Ticket'])->name('user.bookings.ticket');


    Route::get('/user/movies/movie_details/{movie}', [UserController::class, 'showMovie'])->name('user.movies.show');

    // Profile page (for user themselves)
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('/profile/{id}', [UserController::class, 'updateProfile'])->name('user.update_profile'); 
});





Route::middleware(['auth', 'developer'])->prefix('developer')->group(function () {

    // Developer Dashboard
    Route::get('/dashboard', [Controller::class, 'devDashboard'])->name('developer.index');

    // Create Super Admin
    Route::get('/create-super-admin', [SuperAdminController::class, 'showCreateForm'])->name('developer.create_super_admin');
    Route::post('/create-super-admin', [SuperAdminController::class, 'storeSuperAdmin'])->name('developer.store_super_admin');

});
