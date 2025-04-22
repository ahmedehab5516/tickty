<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Showtime;
use App\Models\Hall;
use App\Models\Movie;


class ShowtimeController extends Controller
{
    public function index()
        {
            $showtimes = Showtime::with(['movie', 'hall.cinema'])->latest()->get();
            return view('showtimes.index', compact('showtimes'));
        }
    public function create()
        {
            $movies = \App\Models\Movie::all();
            $halls = \App\Models\Hall::with('cinema')->get();
            return view('showtimes.create', compact('movies', 'halls'));
        }

 
public function store(Request $request)
{
    $request->validate([
        'movie_id'    => 'required|exists:movies,id',
        'hall_id'     => 'required|exists:halls,id',
        'start_time'  => 'required|date',
        'end_time'    => 'required|date|after:start_time',
        'language'    => 'required|string|max:50',
        'is_3d'       => 'nullable|boolean',
        'ticket_price'=> 'required|numeric|min:0',   // ← new
    ]);

    Showtime::create([
        'movie_id'     => $request->movie_id,
        'hall_id'      => $request->hall_id,
        'start_time'   => $request->start_time,
        'end_time'     => $request->end_time,
        'language'     => $request->language,
        'is_3d'        => $request->has('is_3d'),
        'ticket_price' => $request->ticket_price,  // ← new
    ]);

    return redirect()->route('showtimes.index')->with('success', 'Showtime created successfully.');
}
    public function edit($id)
        {
            $showtime = Showtime::findOrFail($id);
            $movies = Movie::all();
            $halls = Hall::with('cinema')->get();

            return view('showtimes.edit', compact('showtime', 'movies', 'halls'));
        }



public function update(Request $request, $id)
{
    $request->validate([
        'movie_id'     => 'required|exists:movies,id',
        'hall_id'      => 'required|exists:halls,id',
        'start_time'   => 'required|date',
        'end_time'     => 'required|date|after:start_time',
        'language'     => 'required|string|max:50',
        'is_3d'        => 'nullable|boolean',
        'ticket_price'=> 'required|numeric|min:0',   // ← new
    ]);

    $showtime = Showtime::findOrFail($id);

    $showtime->update([
        'movie_id'     => $request->movie_id,
        'hall_id'      => $request->hall_id,
        'start_time'   => $request->start_time,
        'end_time'     => $request->end_time,
        'language'     => $request->language,
        'is_3d'        => $request->has('is_3d'),
        'ticket_price' => $request->ticket_price,  // ← new
    ]);

    return redirect()->route('showtimes.index')->with('success', 'Showtime updated successfully.');
}
  public function destroy($id)
            {
                $showtime = Showtime::findOrFail($id);

                // Delete all related seats
                $showtime->bookings()->delete();

                // Delete the hall
                $showtime->delete();

                return redirect()->route('showtimes.index')->with('success', 'showtime and its bookings are deleted successfully.');
            }

}
