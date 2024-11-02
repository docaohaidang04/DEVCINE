<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    MovieController,
    AccountController,
    RoomController,
    ShowtimeController,
    ProductController,
    ProductComboController,
    ChairController,
    ComboController,
    CommentController,
    TicketsController,
    PromotionController,
    GenreMoviesController,
    BookingsController,
    BookingPromotionController,
    PaymentsController,
    ShowtimeSlotController
};

// MOVIES
Route::apiResource('movies', MovieController::class);

// BOOKINGS
Route::apiResource('bookings', BookingsController::class);

// BOOKING PROMOTIONS
Route::apiResource('booking-promotions', BookingPromotionController::class);

// PAYMENTS
Route::apiResource('payments', PaymentsController::class);

// GENRE MOVIES
Route::apiResource('genre_movies', GenreMoviesController::class);

// ACCOUNTS
Route::post('register', [AccountController::class, 'register']);
Route::post('login', [AccountController::class, 'login']);
Route::get('accounts', [AccountController::class, 'showAllAccount']);
Route::get('accounts/{id}', [AccountController::class, 'getAccount']);
Route::put('accounts/{id}', [AccountController::class, 'updateAccount']);
Route::delete('accounts/{id}', [AccountController::class, 'deleteAccount']);

// ROOMS
Route::apiResource('rooms', RoomController::class);
Route::get('rooms/active', [RoomController::class, 'getActiveRooms']);

// SHOWTIMES
Route::apiResource('showtimes', ShowtimeController::class);
Route::get('/movies/{movieId}/next-showtimes', [ShowtimeController::class, 'getNextShowtimes']);

// PRODUCTS
Route::apiResource('products', ProductController::class);

// PRODUCT COMBOS
Route::apiResource('product-combos', ProductComboController::class);

// COMBOS
Route::apiResource('combos', ComboController::class);

// TICKETS
Route::apiResource('tickets', TicketsController::class);

// COMMENTS
Route::apiResource('comments', CommentController::class);

// CHAIRS
Route::apiResource('chairs', ChairController::class);
Route::get('chairs/room/{id_room}', [ChairController::class, 'getChairsByRoom']);

// PROMOTIONS
Route::apiResource('promotions', PromotionController::class);

// SHOWTIMES-SLOT
Route::apiResource('showtime-slots', ShowtimeSlotController::class);
