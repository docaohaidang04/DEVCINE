<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

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

    // Lấy tất cả các thể loại phim
    public static function getAllGenres()
    {
        return self::all();
    }

    // Tạo thể loại phim mới
    public static function createGenre($data)
    {
        // Xác thực dữ liệu
        $validator = Validator::make($data, [
            'genre_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return self::create($data);
    }

    // Lấy thể loại phim theo ID
    public static function findGenreMovies($id)
    {
        return self::findOrFail($id);
    }

    // Cập nhật thể loại phim
    public function updateGenre($data)
    {
        return $this->update($data);
    }

    // Xóa thể loại phim
    public static function deleteGenre($id)
    {
        $genre = self::find($id);
        if ($genre) {
            $genre->delete();
        }
        return $genre;
    }
}
