<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the movies.
     */
    public function index()
    {
        $movies = Movie::with('showtimes')->latest()->get();
        return view('movies.index', compact('movies'));
    }

    /**
     * Show the form for creating a new movie.
     */
    public function create()
    {
        return view('movies.create');
    }

    /**
     * Store a newly created movie in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'duration_hours'   => 'nullable|integer|min:0',
            'duration_mins'    => 'nullable|integer|min:0|max:59',
            'genre'            => 'nullable|string|max:50',
            'rating'           => 'nullable|string|max:10',
            'poster_url'       => 'nullable|url',
            'trailer_url'      => 'nullable|url',
        ]);

        $duration_minutes = ($request->duration_hours * 60) + $request->duration_mins;

        Movie::create([
            'title'            => $request->title,
            'description'      => $request->description,
            'duration_minutes' => $duration_minutes,
            'genre'            => $request->genre,
            'rating'           => $request->rating,
            'poster_url'       => $request->poster_url,
            'trailer_url'      => $request->trailer_url,
        ]);

        return redirect()->route('movies.index')->with('success', 'Movie created successfully.');
    }

    /**
     * Display the specified movie as JSON (for API use).
     */
    public function show($id)
    {
        $movie = Movie::findOrFail($id);
        return response()->json($movie);
    }

    /**
     * Show the form for editing the specified movie.
     */
    public function edit($id)
    {
        $movie = Movie::findOrFail($id);
        return view('movies.edit', compact('movie'));
    }

    /**
     * Update the specified movie in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'duration_hours'   => 'nullable|integer|min:0',
            'duration_mins'    => 'nullable|integer|min:0|max:59',
            'genre'            => 'required|string|max:50',
            'rating'           => 'required|integer|min:1|max:5',
            'poster_url'       => 'nullable|url',
            'trailer_url'      => 'nullable|url',
        ]);

        $movie = Movie::findOrFail($id);
        $movie->update([
            'title'            => $request->title,
            'description'      => $request->description,
            'duration_minutes' => ($request->duration_hours * 60) + $request->duration_mins,
            'genre'            => $request->genre,
            'rating'           => $request->rating,
            'poster_url'       => $request->poster_url,
            'trailer_url'      => $request->trailer_url,
        ]);

        return redirect()->route('movies.index')->with('success', 'Movie updated successfully.');
    }

   
public function destroy($id)
{
    $movie = Movie::findOrFail($id);

    // Delete related showtimes first
    $movie->showtimes()->delete();

    // Delete the movie
    $movie->delete();

    return redirect()->route('movies.index')->with('success', 'Movie and its showtimes were deleted successfully.');
}


     

              
}
