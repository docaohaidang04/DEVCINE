<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        if ($status) {
            return Movie::where('status', $status)->get();
        }
        return Movie::getAllMovies();
    }

    public function store(Request $request)
    {
        $movie = Movie::storeMovie($request);
        return response()->json([
            'movie' => $movie->load('genres'),
            'image_url' => asset($movie->image_main),
        ], 201);
    }

    public function update(Request $request, string $id_movie)
    {
        $movie = Movie::updateMovie($request, $id_movie);
        return response()->json([
            'movie' => $movie->load('genres'),
        ], 200);
    }

    public function show(string $id_movie)
    {
        $movie = Movie::getMovieById($id_movie);
        return response()->json($movie);
    }

    public function destroy(string $id_movie)
    {
        return Movie::deleteMovie($id_movie);
    }
}
