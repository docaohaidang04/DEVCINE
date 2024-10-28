<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        return Movie::getAllMovies();
    }

    public function store(Request $request)
    {
        $request->validate([
            'movie_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer',
            'release_date' => 'required|date',
            'country' => 'required|string|max:255',
            'producer' => 'required|string|max:255',
            'director' => 'required|string|max:255',
            'image_main' => 'nullable|string',
            'youtube_url' => 'nullable|string',
            'cast' => 'required|string',
            'poster_url' => 'nullable|string',
            'price' => 'required|integer',
            'genres' => 'required|array',
            'genres.*' => 'exists:genre_movies,id_genre',
        ]);

        $movie = Movie::create($request->all());

        // Gắn thể loại vào phim
        $movie->genres()->attach($request->genres);

        return response()->json($movie->load('genres'), 201);
    }

    public function show(string $id_movie)
    {
        $movie = Movie::getMovieById($id_movie);
        return response()->json($movie);
    }

    public function update(Request $request, string $id_movie)
    {
        $request->validate([
            'movie_name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'duration' => 'sometimes|required|integer',
            'release_date' => 'sometimes|required|date',
            'country' => 'sometimes|required|string|max:255',
            'producer' => 'sometimes|required|string|max:255',
            'director' => 'sometimes|required|string|max:255',
            'cast' => 'sometimes|required|string',
            'image_main' => 'nullable|string',
            'youtube_url' => 'nullable|string',
            'poster_url' => 'sometimes|nullable|string',
            'price' => 'sometimes|required|integer',
            'genres' => 'sometimes|array',
            'genres.*' => 'exists:genre_movies,id_genre',
        ]);

        $movie = Movie::findOrFail($id_movie);
        $movie->update($request->all());

        // Đồng bộ thể loại
        if ($request->has('genres')) {
            $movie->genres()->sync($request->genres);
        }

        return response()->json($movie->load('genres'), 200);
    }

    public function destroy(string $id_movie)
    {
        return Movie::deleteMovie($id_movie);
    }
}
