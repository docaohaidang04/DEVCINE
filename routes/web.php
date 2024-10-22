<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ChairController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\PromotionController;

//MOVIES
Route::get('movies', [MovieController::class, 'index']);
Route::post('movies', [MovieController::class, 'store']);
Route::get('movies/{ID_MOVIE}', [MovieController::class, 'show']);
Route::put('movies/{id}', [MovieController::class, 'update']);
Route::delete('movies/{id}', [MovieController::class, 'destroy']);

//ACCOUNTS
Route::post('register', [AccountController::class, 'register']);
Route::post('login', [AccountController::class, 'login']);
Route::get('accounts/{id}', [AccountController::class, 'getAccount']);
Route::put('accounts/{id}', [AccountController::class, 'updateAccount']);
Route::delete('accounts/{id}', [AccountController::class, 'deleteAccount']);

//ROOM
Route::get('rooms', [RoomController::class, 'index']);
Route::get('rooms/active', [RoomController::class, 'getActiveRooms']);
Route::get('rooms/{id}', [RoomController::class, 'show']);
Route::post('rooms', [RoomController::class, 'store']);
Route::put('rooms/{id}', [RoomController::class, 'update']);
Route::delete('rooms/{id}', [RoomController::class, 'destroy']);

//SHOWTIME
Route::get('showtimes', [ShowtimeController::class, 'index']);
Route::get('showtimes/{id}', [ShowtimeController::class, 'show']);
Route::post('showtimes', [ShowtimeController::class, 'store']);
Route::put('showtimes/{id}', [ShowtimeController::class, 'update']);
Route::delete('showtimes/{id}', [ShowtimeController::class, 'destroy']);

//PRODUCT
Route::get('products', [ProductController::class, 'index']);
Route::post('products', [ProductController::class, 'store']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::put('products/{id}', [ProductController::class, 'update']);
Route::delete('products/{id}', [ProductController::class, 'destroy']);

//CHAIR
Route::get('/chairs', [ChairController::class, 'index'])->name('chairs.index');
Route::post('/chairs', [ChairController::class, 'store'])->name('chairs.store');
Route::get('/chairs/{id}', [ChairController::class, 'show'])->name('chairs.show');
Route::put('/chairs/{id}', [ChairController::class, 'update'])->name('chairs.update');
Route::delete('/chairs/{id}', [ChairController::class, 'destroy'])->name('chairs.destroy');

//COMBO
Route::get('/combos', [ComboController::class, 'index'])->name('combos.index');

//
Route::get('/promotions', [PromotionController::class, 'index']);
Route::post('/promotions', [PromotionController::class, 'store']);
Route::get('/promotions/{promotion}', [PromotionController::class, 'show']);
Route::put('/promotions/{promotion}', [PromotionController::class, 'update']);
Route::delete('/promotions/{promotion}', [PromotionController::class, 'destroy']);