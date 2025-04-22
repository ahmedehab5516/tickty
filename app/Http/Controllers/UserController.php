<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Movie;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;


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
                // Retrieve the movie with its showtimes and each hall’s seats,
                // but only seats that aren’t 'not_assigned'
                $movie = Movie::with([
                    'showtimes.hall.seats' => function($q) {
                        $q->where('seat_type', '!=', 'not_assigned');
                    }
                ])->findOrFail($id);

                return view('user.movies.show', compact('movie'));
            }

        
public function pay(Request $request)
{
    // Validate that we got exactly what we need
    $request->validate([
        'movie_id'    => 'required|exists:movies,id',
        'showtime_id' => 'required|exists:showtimes,id',
        'seats'       => 'required|json',
    ]);

    // Load the movie & showtime
    $movie    = Movie::findOrFail($request->movie_id);
    $showtime = Showtime::findOrFail($request->showtime_id);

    // Decode the JSON array of seat IDs
    $seatIds = json_decode($request->seats, true);

    // Load those Seat models
    $seats = Seat::whereIn('id', $seatIds)->get();

    // Pass them all to the view, plus the per‐ticket price
    return view('user.bookings.pay', [
        'movie'            => $movie,
        'showtime'         => $showtime,
        'seats'            => $seats,
        'price_per_ticket' => $showtime->ticket_price,
        'total' => $request->total_price, // 👈 Pass it directly from the request
    ]);
}

   public function Stripe(Request $request)
{
    $request->validate([
        'movie_id'     => 'required|exists:movies,id',
        'showtime_id'  => 'required|exists:showtimes,id',
        'seats'        => 'required|json',
        'stripeToken'  => 'required',
        'price'        => 'required|numeric',
    ]);

    $seatIds = json_decode($request->seats, true);

    // ✅ Check if any of the selected seats are already booked
    foreach ($seatIds as $seatId) {
        $alreadyBooked = Booking::where('showtime_id', $request->showtime_id)
            ->whereJsonContains('seats', $seatId)
            ->exists();

        if ($alreadyBooked) {
            return back()->withErrors(['seats' => "Seat ID $seatId is already booked."])->withInput();
        }
    }

    // ✅ Create one booking that stores all selected seat IDs
    $booking = Booking::create([
        'user_id'     => auth()->id(),
        'showtime_id' => $request->showtime_id,
        'seats'       => $seatIds, // stored as JSON
        'status'      => 'confirmed',
    ]);

    // ✅ Create Stripe customer & charge
    $stripe = new \Stripe\StripeClient(env("STRIPE_SECERT"));

    $customer = $stripe->customers->create([
        'name'  => auth()->user()->name,
        'email' => auth()->user()->email,
        'source' => $request->stripeToken,
    ]);

    $charge = $stripe->charges->create([
        'amount'      => $request->price * 100,
        'currency'    => 'usd',
        'customer'    => $customer->id,
        'description' => "Payment for booking tickets",
    ]);

    // ✅ Create one payment record
    Payment::create([
        'booking_id'         => $booking->id,
        'user_id'            => auth()->id(),
        'amount'             => $request->price,
        'method'             => 'card',
        'status'             => $charge->status === 'succeeded' ? 'paid' : 'failed',
        'currency'           => $charge->currency,
        'card_brand'         => $charge->payment_method_details['card']['brand'] ?? null,
        'card_last4'         => $charge->payment_method_details['card']['last4'] ?? null,
        'transaction_id'     => $charge->id,
        'stripe_receipt_url' => $charge->receipt_url,
        'paid_at'            => now(),
    ]);

    return redirect()->route('user.bookings.ticket', [
        'booking_id' => $booking->id,
    ]);

}


        public function Ticket(Request $request)
        {
            $booking = Booking::with('showtime', 'payment')->findOrFail($request->booking_id);
            $movie = Movie::findOrFail($booking->showtime->movie_id);
            $payment = $booking->payment;

            return view('user.bookings.ticket', compact('booking', 'movie', 'payment'));
        }



    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }




}



// Visa	4242424242424242

// Visa (debit)	4000056655665556

// Mastercard	5555555555554444

// Mastercard (2-series)	2223003122003222

// Mastercard (debit)	5200828282828210

// Mastercard (prepaid)	5105105105105100

// American Express	371449635398431

// Discover	6011000990139424