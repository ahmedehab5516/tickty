<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Movie;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Payment;

class UserController extends Controller
{
    public function index()
    {
        return view('user.dashboard');
    }

    public function bookingIndex()
    {
        $user = auth()->user();

        $bookings = Booking::with(['showtime.movie', 'showtime.hall', 'seat'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('user.bookings.index', compact('bookings'));
    }

    public function moviesIndex()
    {
        $movies = Movie::latest()->get();
        return view('user.movies.index', compact('movies'));
    }

    public function showMovie($id)
    {
        $movie = Movie::with('showtimes.hall')->findOrFail($id);
        return view('user.movies.show', compact('movie'));
    }

public function chooseSeat(Movie $movie, Showtime $showtime)
{
    // Fetch all seats related to the showtime's hall
    $seats = Seat::where('hall_id', $showtime->hall_id)->get();

    // Group seats by row for easier layout
    $groupedSeats = $seats->groupBy('seat_row')->sortKeys();

    // Get the max number of columns (seats per row)
    $maxColumns = $seats->max('seat_column');

    // Fetch booked seats for this showtime
    $bookedSeatIds = Booking::where('showtime_id', $showtime->id)->pluck('seat_id')->toArray();

    // Get available seat rows for dropdown
    $seatRows = $groupedSeats->keys();

    // Return the view with all the data
    return view('user.bookings.choose_seat', compact(
        'movie',
        'showtime',
        'seats',
        'groupedSeats',
        'maxColumns',
        'bookedSeatIds',
        'seatRows'
    ));
}


    public function pay(Movie $movie, Showtime $showtime, Seat $seat)
    {
        return view('user.bookings.payment', [
            'movie' => $movie,
            'showtime' => $showtime,
            'seat' => $seat,
            'price' => 50.00 // Flat or dynamic pricing logic can go here
        ]);
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'movie_id'     => 'required|exists:movies,id',
            'showtime_id'  => 'required|exists:showtimes,id',
            'seat_id'      => 'required|exists:seats,id',
            'method'       => 'required|in:cash,card,wallet',
        ]);

        // Check for existing booking for this seat and showtime
        $alreadyBooked = Booking::where('showtime_id', $request->showtime_id)
            ->where('seat_id', $request->seat_id)
            ->exists();

        if ($alreadyBooked) {
            return back()->withErrors(['seat_id' => 'This seat is already booked.'])->withInput();
        }

        // Create booking
        $booking = Booking::create([
            'user_id'     => auth()->id(),
            'showtime_id' => $request->showtime_id,
            'seat_id'     => $request->seat_id,
            'status'      => 'confirmed',
        ]);

        // Set payment amount (flat for now)
        $amount = 100.00;

        // Create payment record
        Payment::create([
            'booking_id'     => $booking->id,
            'amount'         => $amount,
            'method'         => $request->method,
            'status'         => 'pending',
            'transaction_id' => null,
        ]);

        return redirect()->route('user.bookings.index')->with('success', '🎉 Booking confirmed successfully.');
    }
}
