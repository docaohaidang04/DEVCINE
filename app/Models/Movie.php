<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'poster_url',
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
        return self::with('genres')->get(); // Đổi 'genre' thành 'genres' để khớp với quan hệ
    }

    public static function getMovieById($id_movie)
    {
        try {
            return self::with(['genres', 'showtimes' => function ($query) {
                $query->where('date_time', '>=', Carbon::now())
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

        $movie->delete();
        return response()->json(['message' => 'Movie deleted successfully'], 204);
    }
}
