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
        'Genre_name',
    ];

    public static function findGenreMovies($id)
    {
        return self::findOrFail($id);
    }
}
