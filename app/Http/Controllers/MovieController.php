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
        Log::info('Request data:', $request->all()); // Logging dữ liệu nhận được

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
            'genres' => 'required|array',
            'genres.*' => 'exists:genre_movies,id_genre',
        ]);

        // Kiểm tra và tạo thư mục nếu chưa tồn tại
        $destinationPath = public_path('movies');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Khởi tạo các biến để lưu tên tệp
        $imageMainName = null;
        $posterUrlName = null;

        // Lưu ảnh vào public/movies nếu có
        if ($request->hasFile('image_main')) {
            $imageMainName = time() . '_' . $request->file('image_main')->getClientOriginalName();
            $request->file('image_main')->move($destinationPath, $imageMainName);
        }

        // Lưu ảnh vào public/movies nếu có
        if ($request->hasFile('poster_url')) {
            $posterUrlName = time() . '_' . $request->file('poster_url')->getClientOriginalName();
            $request->file('poster_url')->move($destinationPath, $posterUrlName);
        }

        // Tạo mới bản ghi phim
        $movie = Movie::create([
            'movie_name' => $request->movie_name,
            'description' => $request->description,
            'duration' => $request->duration,
            'release_date' => $request->release_date,
            'country' => $request->country,
            'producer' => $request->producer,
            'director' => $request->director,
            'cast' => $request->cast,
            'id_genre' => $request->id_genre,
            'status' => $request->status,
            'youtube_url' => $request->youtube_url,
            'image_main' => $imageMainName ? 'movies/' . $imageMainName : null,
            'poster_url' => $posterUrlName ? 'movies/' . $posterUrlName : null,
        ]);

        // Gắn thể loại vào phim
        $movie->genres()->attach($request->genres);
        Log::info('add movie with data:', $request->all()); // Logging thông tin cập nhật

        // Trả về response kèm đường dẫn ảnh
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
            'youtube_url'
        ]));

        // Đồng bộ thể loại nếu có
        if ($request->has('genres')) {
            $movie->genres()->sync($request->genres);
        }

        Log::info('Updated movie with data:', $request->all()); // Logging thông tin cập nhật

        // Trả về response kèm đường dẫn ảnh
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
