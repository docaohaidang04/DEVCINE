<?php

namespace App\Http\Controllers;

use App\Models\GenreMovies;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GenreMoviesController extends Controller
{
    // Lấy danh sách tất cả các thể loại phim
    public function index(): JsonResponse
    {
        return response()->json(GenreMovies::getAllGenres());
    }

    // Tạo thể loại phim mới
    public function store(Request $request): JsonResponse
    {
        $genreMovies = GenreMovies::createGenre($request->all()); // Gọi phương thức trong model
        if ($genreMovies instanceof \Illuminate\Http\JsonResponse) {
            return $genreMovies; // Trả về lỗi nếu có
        }

        return response()->json($genreMovies, 201);
    }

    // Lấy thể loại phim theo ID
    public function show($id): JsonResponse
    {
        $genreMovies = GenreMovies::findGenreMovies($id);
        return response()->json($genreMovies);
    }

    // Cập nhật thể loại phim theo ID
    public function update(Request $request, $id): JsonResponse
    {
        $genreMovies = GenreMovies::findGenreMovies($id);
        if (!$genreMovies) {
            return response()->json(['message' => 'Genre not found'], 404);
        }

        $genreMovies->updateGenre($request->all()); // Gọi phương thức trong model
        return response()->json($genreMovies);
    }

    // Xóa thể loại phim theo ID
    public function destroy($id): JsonResponse
    {
        $genreMovies = GenreMovies::deleteGenre($id);
        if (!$genreMovies) {
            return response()->json(['message' => 'Genre not found'], 404);
        }

        return response()->json(['message' => 'Genre deleted']);
    }
}
