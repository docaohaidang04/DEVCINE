<?php

namespace App\Http\Controllers;

use App\Models\GenreMovies;
use Illuminate\Http\Request;

class GenreMoviesController extends Controller
{
    public function index()
    {
        return GenreMovies::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'genre_name' => 'required|string|max:255',
        ]);

        $genreMovies = GenreMovies::create($request->all());
        return response()->json($genreMovies, 201);
    }

    public function show($id)
    {
        return GenreMovies::findGenreMovies($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'genre_name' => 'string|max:255',
        ]);

        $genreMovies = GenreMovies::findOrFail($id);
        $genreMovies->update($request->all());
        return response()->json($genreMovies, 200);
    }

    public function destroy($id)
    {
        GenreMovies::destroy($id);
        return response()->json(null, 204);
    }
}
