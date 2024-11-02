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
        // Validate các dữ liệu đầu vào
        $request->validate([
            'movie_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer',
            'release_date' => 'required|date',
            'country' => 'required|string|max:255',
            'producer' => 'required|string|max:255',
            'director' => 'required|string|max:255',
            'image_main' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'youtube_url' => 'nullable|string',
            'cast' => 'required|string',
            'status' => 'required|string',
            'poster_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|integer',
            'genres' => 'required|array',
            'genres.*' => 'exists:genre_movies,id_genre',
        ]);

        // Lưu ảnh vào public/movies nếu có
        if ($request->hasFile('image_main')) {
            $imageName = time() . '.' . $request->file('image_main')->getClientOriginalExtension();
            $request->file('image_main')->move(public_path('movies'), $imageName);
            $request->merge(['image_main' => 'movies/' . $imageName]);
        }

        // Tạo mới bản ghi phim
        $movie = Movie::create($request->all());

        // Gắn thể loại vào phim
        $movie->genres()->attach($request->genres);

        // Trả về response kèm đường dẫn ảnh
        return response()->json([
            'movie' => $movie->load('genres'),
            'image_url' => asset($movie->image_main),
        ], 201);
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
            'image_main' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|string',
            'youtube_url' => 'nullable|string',
            'poster_url' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'sometimes|required|integer',
            'genres' => 'sometimes|array',
            'genres.*' => 'exists:genre_movies,id_genre',
        ]);

        // Tìm phim theo id
        $movie = Movie::findOrFail($id_movie);

        // Lưu ảnh mới vào public/movies nếu có
        if ($request->hasFile('image_main')) {
            $imageName = time() . '.' . $request->file('image_main')->getClientOriginalExtension();
            $request->file('image_main')->move(public_path('movies'), $imageName);
            $request->merge(['image_main' => 'movies/' . $imageName]);
        }

        // Cập nhật thông tin phim
        $movie->update($request->all());

        // Đồng bộ thể loại nếu có
        if ($request->has('genres')) {
            $movie->genres()->sync($request->genres);
        }

        // Trả về response kèm đường dẫn ảnh
        return response()->json([
            'movie' => $movie->load('genres'),
            'image_url' => asset($movie->image_main),
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
