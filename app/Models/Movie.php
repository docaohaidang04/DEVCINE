<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

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
        'id_genre',
        'director',
        'cast',
        'poster_url',
        'price', // Thêm trường Price vào đây
    ];

    public function genre()
    {
        return $this->belongsTo(GenreMovies::class, 'id_genre', 'id_genre');
    }

    public static function getAllMovies()
    {
        return self::with('genre')->get();
    }

    public static function createMovie($data)
    {
        $validator = Validator::make($data, [
            'movie_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer',
            'release_date' => 'required|date',
            'country' => 'required|string|max:255',
            'producer' => 'required|string|max:255',
            'id_genre' => 'required|exists:genre_movies,id_genre',
            'director' => 'required|string|max:255',
            'cast' => 'required|string',
            'poster_url' => 'nullable|string',
            'price' => 'required|integer', // Thêm validation cho Price
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return self::create($data);
    }

    public static function getMovieById($id_movie)
    {
        return self::with('genre')->where('id_movie', $id_movie)->firstOrFail();
    }

    public static function updateMovie($id_movie, $data)
    {
        $movie = self::findOrFail($id_movie);

        $validator = Validator::make($data, [
            'movie_name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'duration' => 'sometimes|required|integer',
            'release_date' => 'sometimes|required|date',
            'country' => 'sometimes|required|string|max:255',
            'producer' => 'sometimes|required|string|max:255',
            'id_genre' => 'sometimes|required|exists:genre_movies,id_genre',  // Kiểm tra ID thể loại hợp lệ
            'director' => 'sometimes|required|string|max:255',
            'cast' => 'sometimes|required|string',
            'poster_url' => 'sometimes|nullable|string',
            'price' => 'sometimes|required|integer', // Thêm validation cho Price
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $movie->update($data);
        return $movie;
    }

    public static function deleteMovie($id_movie)
    {
        $movie = self::findOrFail($id_movie);
        $movie->delete();
        return null;
    }
}
