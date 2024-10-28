<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'cast',
        'poster_url',
        'price',
    ];

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
        return self::with('genres')->where('id_movie', $id_movie)->firstOrFail(); // Đổi 'genre' thành 'genres'
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
