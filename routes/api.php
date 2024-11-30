<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    MovieController,
    AccountController,
    AuthController,
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
    PaymentsController,
    ShowtimeSlotController,
    VNPayController,
    StatisticsController,
    CountryController
};
use Illuminate\Container\Attributes\Auth;
use App\Http\Controllers\Auth\PasswordResetController;

Route::post('/password-forgot', [PasswordResetController::class, 'forgotPassword']);
Route::post('/password-reset', [PasswordResetController::class, 'resetPassword']);
Route::get('/password-reset', [PasswordResetController::class, 'showResetForm']);

Route::get('countries', [CountryController::class, 'getCountries']);

// MOVIES
Route::apiResource('movies', MovieController::class);

// BOOKINGS
Route::apiResource('bookings', BookingsController::class);
Route::get('/bookings/account/{id}', [BookingsController::class, 'getBookingsByAccount']);

// PAYMENTS
Route::apiResource('payments', PaymentsController::class);
Route::post('/vnpay/payment', [VNPayController::class, 'createPayment']);
Route::get('/vnpay/return', [VNPayController::class, 'paymentCallback']);

// GENRE MOVIES
Route::apiResource('genre_movies', GenreMoviesController::class);

// ACCOUNTS
Route::post('register', [AccountController::class, 'register']);
Route::get('accounts-verify/{token}', [AccountController::class, 'verify']);
/* Route::post('register', [AccountController::class, 'register']); */
/* Route::post('login', [AccountController::class, 'login']);
 */
//login
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function () {
    Route::post('login', [
        AuthController::class,
        'login'
    ]);
});
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

// COMBOS
Route::apiResource('combos', ComboController::class);

// TICKETS
Route::apiResource('tickets', TicketsController::class);

// COMMENTS
Route::apiResource('comments', CommentController::class);
Route::get('comments-movie/{id_movie}', [CommentController::class, 'getCommentsByMovieId']);

// CHAIRS
Route::apiResource('chairs', ChairController::class);
Route::get('chairs/room/{id_room}', [ChairController::class, 'getChairsByRoom']);
Route::post('/tickets-book/{showtime_id}/{chair_id}', [TicketsController::class, 'bookChair']);

// PROMOTIONS
Route::apiResource('promotions', PromotionController::class);

// SHOWTIMES-SLOT
Route::apiResource('showtime-slots', ShowtimeSlotController::class);

// STATISTICS
Route::prefix('statistics')->group(function () {
    Route::get('/ticket-sales', [StatisticsController::class, 'ticketSalesByDay']);
    Route::get('/revenue', [StatisticsController::class, 'revenueStatistics']);
    Route::get('/revenue-by-movie', [StatisticsController::class, 'revenueByMovie']);
});
