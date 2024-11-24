<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Movie extends Model
{
    use HasFactory;

    protected $table = 'movies';
    protected $primaryKey = 'id_movie';

    protected $fillable = [
        'movie_name',
        'description',
        'duration',
        'release_date',
        'country',
        'producer',
        'director',
        'image_main',
        'youtube_url',
        'cast',
        'status',
        'poster_url'
    ];

    // Định nghĩa quan hệ showtimes
    public function showtimes()
    {
        return $this->hasMany(Showtime::class, 'id_movie', 'id_movie');
    }

    public function genres()
    {
        return $this->belongsToMany(GenreMovies::class, 'genre_movie', 'movie_id', 'genre_id');
    }

    public static function getAllMovies()
    {
        return self::with('genres')->get();
    }

    public static function getMovieById($id_movie)
    {
        try {
            return self::with(['genres', 'showtimes' => function ($query) {
                $query->where('date_time', '>=', Carbon::today())
                    ->orderBy('date_time')
                    ->take(5);
            }, 'showtimes.showtimeSlots'])
                ->where('id_movie', $id_movie)
                ->firstOrFail();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public static function deleteMovie($id_movie)
    {
        $movie = self::find($id_movie);
        if (!$movie) {
            return response()->json(['error' => 'Movie not found'], 404);
        }

        // Xóa ảnh cũ nếu có
        if ($movie->image_main && file_exists(public_path($movie->image_main))) {
            unlink(public_path($movie->image_main));
        }

        if ($movie->poster_url && file_exists(public_path($movie->poster_url))) {
            unlink(public_path($movie->poster_url));
        }

        $movie->delete();
        return response()->json(['message' => 'Movie deleted successfully'], 204);
    }

    // Thêm mới phim và xử lý file
    public static function storeMovie($request)
    {
        // Validate dữ liệu
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

        // Xử lý lưu file ảnh
        $imageMainName = $request->hasFile('image_main') ? self::uploadImage($request->file('image_main'), $destinationPath) : null;
        $posterUrlName = $request->hasFile('poster_url') ? self::uploadImage($request->file('poster_url'), $destinationPath) : null;

        // Tạo mới bản ghi phim
        $movie = self::create([
            'movie_name' => $request->movie_name,
            'description' => $request->description,
            'duration' => $request->duration,
            'release_date' => $request->release_date,
            'country' => $request->country,
            'producer' => $request->producer,
            'director' => $request->director,
            'cast' => $request->cast,
            'status' => $request->status,
            'youtube_url' => $request->youtube_url,
            'image_main' => $imageMainName ? 'movies/' . $imageMainName : null,
            'poster_url' => $posterUrlName ? 'movies/' . $posterUrlName : null,
        ]);

        // Gắn thể loại vào phim
        $movie->genres()->attach($request->genres);

        return $movie;
    }

    // Cập nhật thông tin phim và xử lý file
    public static function updateMovie($request, $id_movie)
    {
        // Validate dữ liệu
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

        $movie = self::findOrFail($id_movie);

        // Xử lý cập nhật ảnh
        if ($request->hasFile('image_main')) {
            self::deleteImageIfExists($movie->image_main);
            $movie->image_main = 'movies/' . self::uploadImage($request->file('image_main'), public_path('movies'));
        }

        if ($request->hasFile('poster_url')) {
            self::deleteImageIfExists($movie->poster_url);
            $movie->poster_url = 'movies/' . self::uploadImage($request->file('poster_url'), public_path('movies'));
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

        return $movie;
    }

    // Hỗ trợ upload ảnh
    private static function uploadImage($image, $path)
    {
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move($path, $imageName);
        return $imageName;
    }

    // Hỗ trợ xóa ảnh
    private static function deleteImageIfExists($imagePath)
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }
    }
}
