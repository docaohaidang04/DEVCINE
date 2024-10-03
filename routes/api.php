<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

Route::get('/api/movies', [MovieController::class, 'index']);       // Lấy tất cả movies
Route::post('/api/movies', [MovieController::class, 'store']);      // Tạo movie mới
Route::get('/api/movies/{id}', [MovieController::class, 'show']);   // Lấy thông tin movie theo id
Route::put('/api/movies/{id}', [MovieController::class, 'update']); // Cập nhật movie theo id
Route::delete('/api/movies/{id}', [MovieController::class, 'destroy']); // Xóa movie theo id