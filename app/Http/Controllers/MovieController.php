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
        $movie = Movie::createMovie($request->all());
        return response()->json($movie, 201);
    }

    public function show(string $id_movie)
    {
        $movie = Movie::getMovieById($id_movie);
        return response()->json($movie);
    }

    public function update(Request $request, string $id_movie)
    {
        $movie = Movie::updateMovie($id_movie, $request->all());
        return response()->json($movie, 200);
    }

    public function destroy(string $id_movie)
    {
        $response = Movie::deleteMovie($id_movie);
        return response()->json($response, 204);
    }
}
