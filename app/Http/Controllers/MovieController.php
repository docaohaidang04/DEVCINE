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

    public function show(string $id)
    {
        $movie = Movie::getMovieById($id);
        return response()->json($movie);
    }

    public function update(Request $request, string $id)
    {
        $movie = Movie::updateMovie($id, $request->all());
        return response()->json($movie, 200);
    }

    public function destroy(string $id)
    {
        Movie::deleteMovie($id);
        return response()->json(null, 204);
    }
}