<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductComboController;
use App\Http\Controllers\ChairController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\GenreMoviesController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\BookingPromotionController;
use App\Http\Controllers\PaymentsController;

//MOVIES
Route::get('movies', [MovieController::class, 'index']);
Route::post('movies', [MovieController::class, 'store']);
Route::get('movies/{id}', [MovieController::class, 'show']);
Route::put('movies/{id}', [MovieController::class, 'update']);
Route::delete('movies/{id}', [MovieController::class, 'destroy']);

//BOOKINGS
Route::get('/bookings', [BookingsController::class, 'index']);
Route::get('/bookings/{id}', [BookingsController::class, 'show']);
Route::post('/bookings', [BookingsController::class, 'store']);
Route::put('/bookings/{id}', [BookingsController::class, 'update']);
Route::delete('/bookings/{id}', [BookingsController::class, 'destroy']);

//BOOKING PROMOTION
Route::get('/booking-promotions', [BookingPromotionController::class, 'index']);
Route::get('/booking-promotions/{id}', [BookingPromotionController::class, 'show']);
Route::post('/booking-promotions', [BookingPromotionController::class, 'store']);
Route::put('/booking-promotions/{id}', [BookingPromotionController::class, 'update']);
Route::delete('/booking-promotions/{id}', [BookingPromotionController::class, 'destroy']);

//PAYMENT
Route::get('/payments', [PaymentsController::class, 'index']);
Route::get('/payments/{id}', [PaymentsController::class, 'show']);
Route::post('/payments', [PaymentsController::class, 'store']);
Route::put('/payments/{id}', [PaymentsController::class, 'update']);
Route::delete('/payments/{id}', [PaymentsController::class, 'destroy']);

//GENRE MOVIES
Route::get('genre_movies', [GenreMoviesController::class, 'index']);
Route::post('genre_movies', [GenreMoviesController::class, 'store']);
Route::get('genre_movies/{id}', [GenreMoviesController::class, 'show']);
Route::put('genre_movies/{id}', [GenreMoviesController::class, 'update']);
Route::delete('genre_movies/{id}', [GenreMoviesController::class, 'destroy']);

//ACCOUNTS
Route::post('register', [AccountController::class, 'register']);
Route::post('login', [AccountController::class, 'login']);
Route::get('accounts', [AccountController::class, 'showAllAccount']);
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

//PRODUCTCOMBO
Route::get('product-combos', [ProductComboController::class, 'index']);
Route::get('product-combos/{id}', [ProductComboController::class, 'show']);
Route::post('product-combos', [ProductComboController::class, 'store']);
Route::put('product-combos/{id}', [ProductComboController::class, 'update']);
Route::delete('product-combos/{id}', [ProductComboController::class, 'destroy']);

//COMBO
Route::get('combos', [ComboController::class, 'index']);
Route::post('combos', [ComboController::class, 'store']);
Route::get('combos/{id}', [ComboController::class, 'show']);
Route::put('combos/{id}', [ComboController::class, 'update']);
Route::delete('combos/{id}', [ComboController::class, 'destroy']);

//TICKETS
Route::get('tickets', [TicketsController::class, 'index']);
Route::post('tickets', [TicketsController::class, 'store']);
Route::get('tickets/{id}', [TicketsController::class, 'show']);
Route::put('tickets/{id}', [TicketsController::class, 'update']);
Route::delete('tickets/{id}', [TicketsController::class, 'destroy']);

//COMMENT
Route::get('comment', [CommentController::class, 'index']);
Route::post('comment', [CommentController::class, 'store']);
Route::get('comment/{id}', [CommentController::class, 'show']);
Route::put('comment/{id}', [CommentController::class, 'update']);
Route::delete('comment/{id}', [CommentController::class, 'destroy']);

//CHAIR
Route::get('chairs', [ChairController::class, 'index']);
Route::post('chairs', [ChairController::class, 'store']);
Route::get('chairs/{id}', [ChairController::class, 'show']);
Route::put('chairs/{id}', [ChairController::class, 'update']);
Route::delete('chairs/{id}', [ChairController::class, 'destroy']);
Route::get('chairs/room/{id_room}', [ChairController::class, 'getChairsByRoom']);


//PROMOTIONS
Route::get('/promotions', [PromotionController::class, 'index']);
Route::post('/promotions', [PromotionController::class, 'store']);
Route::get('/promotions/{id}', [PromotionController::class, 'show']);
Route::put('/promotions/{id}', [PromotionController::class, 'update']);
Route::delete('/promotions/{id}', [PromotionController::class, 'destroy']);
