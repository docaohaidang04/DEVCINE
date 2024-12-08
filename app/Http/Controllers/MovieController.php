<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        Log::info('Request data:', $request->all()); // Logging dữ liệu nhận được

        // Validate các dữ liệu đầu vào
        $request->validate([
            'movie_name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'duration' => 'sometimes|required|integer',
            'release_date' => 'sometimes|required|date',
            'country' => 'sometimes|required|string|max:255',
            'producer' => 'sometimes|required|string|max:255',
            'director' => 'sometimes|required|string|max:255',
            'cast' => 'sometimes|required|string',
            'status' => 'sometimes|required|string',
            'youtube_url' => 'sometimes|nullable|string',
            'image_main' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'poster_url' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'genres' => 'sometimes|array',
            'genres.*' => 'exists:genre_movies,id_genre',
        ]);

        // Tìm phim theo id
        $movie = Movie::findOrFail($id_movie);

        // Khởi tạo các biến để lưu tên tệp
        $imageMainName = null;
        $posterUrlName = null;

        // Lưu ảnh mới vào public/movies nếu có
        if ($request->hasFile('image_main')) {
            // Xóa ảnh cũ nếu cần thiết
            if ($movie->image_main && file_exists(public_path($movie->image_main))) {
                unlink(public_path($movie->image_main)); // Xóa tệp cũ
            }
            $imageMainName = time() . '_' . $request->file('image_main')->getClientOriginalName();
            $request->file('image_main')->move(public_path('movies'), $imageMainName);
            $movie->image_main = 'movies/' . $imageMainName; // Cập nhật đường dẫn ảnh
        }

        // Lưu ảnh poster mới nếu có
        if ($request->hasFile('poster_url')) {
            // Xóa poster cũ nếu cần thiết
            if ($movie->poster_url && file_exists(public_path($movie->poster_url))) {
                unlink(public_path($movie->poster_url)); // Xóa tệp cũ
            }
            $posterUrlName = time() . '_' . $request->file('poster_url')->getClientOriginalName();
            $request->file('poster_url')->move(public_path('movies'), $posterUrlName);
            $movie->poster_url = 'movies/' . $posterUrlName; // Cập nhật đường dẫn poster
        }

        // Cập nhật thông tin phim
        $movie->update($request->only([
            'movie_name',
            'description',
            'duration',
            'release_date',
            'country',
            'producer',
            'director',
            'cast',
            'status',
            'youtube_url',
        ]));

        // Đồng bộ thể loại nếu có
        if ($request->has('genres')) {
            $movie->genres()->sync($request->genres);
        }

        Log::info('Updated movie with data:', $request->all()); // Logging thông tin cập nhật

        // Trả về response kèm đường dẫn ảnh

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
