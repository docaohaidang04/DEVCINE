<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    MovieController,
    AccountController,
    AuthController,
    RoomController,
    ShowtimeController,
    ProductController,
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
    CountryController,
    MomoController,
    SeatController
};
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\PasswordResetController;



Route::middleware(['web'])->get('/google-login', [GoogleController::class, 'getGoogleSignInUrl']);
Route::middleware(['web'])->get('/google-callback', [GoogleController::class, 'loginCallback']);

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

Route::post('/momo-payment', [MomoController::class, 'createMomoPayment']);
Route::get('/momo-return', [MomoController::class, 'handleMoMoReturn']);
Route::post('/momo-ipn', [MomoController::class, 'handleMoMoIPN']);

// GENRE MOVIES
Route::apiResource('genre_movies', GenreMoviesController::class);

// ACCOUNTS

Route::get('accounts-verify/{token}', [AccountController::class, 'verify']);
Route::post('register', [AccountController::class, 'register']);
/* Route::post('/auth/login', [AccountController::class, 'login']); */

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
Route::get('/comments/{id_movie}/rating-content', [CommentController::class, 'getRatingSummaryByMovieId']);

// CHAIRS
Route::apiResource('chairs', ChairController::class);
Route::get('chairs/room/{id_room}', [ChairController::class, 'getChairsByRoom']);
Route::post('/tickets-book/{showtime_id}/{chair_id}', [TicketsController::class, 'bookChair']);
Route::post('/book-chair', [ChairController::class, 'bookChair']);
Route::get('/seats', [SeatController::class, 'getSeats']);
Route::get('/showtimes/{id_showtime}/chairs', [ShowtimeController::class, 'getChairsByShowtime']);
Route::put('/showtimes/{id_showtime}/chairs', [ShowtimeController::class, 'updateChairsByShowtime']);

// PROMOTIONS
Route::apiResource('promotions', PromotionController::class);
Route::get('/promotions/{id_account}/account', [PromotionController::class, 'getPromotionByIdAccount']);


// SHOWTIMES-SLOT
Route::apiResource('showtime-slots', ShowtimeSlotController::class);
Route::get('/available-slots', [ShowtimeController::class, 'getAvailableSlots']);

// STATISTICS
Route::prefix('statistics')->group(function () {
    Route::get('/ticket-sales', [StatisticsController::class, 'ticketSalesByDay']);
    Route::get('/revenue', [StatisticsController::class, 'revenueByDate']);
    Route::get('/revenue-by-movie', [StatisticsController::class, 'getRevenueByMovie']);
});

Route::post('/redeem-discount', [AccountController::class, 'redeemDiscountCode']);
