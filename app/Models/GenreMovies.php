<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenreMovies extends Model
{
    use HasFactory;

    protected $table = 'genre_movies';
    protected $primaryKey = 'id_genre';

    protected $fillable = [
        'genre_name',
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'genre_movie', 'genre_id', 'movie_id');
    }

    public static function findGenreMovies($id)
    {
        return self::findOrFail($id);
    }
}
