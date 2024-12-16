<?php

namespace App\Http;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Các middleware toàn cục cho ứng dụng.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        /* \Illuminate\Http\Middleware\PreventRequestsDuringMaintenance::class, // Đảm bảo lớp này có sẵn */
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        /*   \App\Http\Middleware\TrimStrings::class, */
        /*  \Illuminate\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class, */
    ];

    /**
     * Các nhóm middleware cho ứng dụng.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            /* \App\Http\Middleware\EncryptCookies::class, */
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            /*  \App\Http\Middleware\VerifyCsrfToken::class, */
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Tymon\JWTAuth\Http\Middleware\Authenticate::class,  // Để vẫn tương thích (trường hợp nếu bạn đang ở phiên bản cũ)
            \Tymon\JWTAuth\Http\Middleware\RefreshToken::class,  // Thêm middleware mới nếu có
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Các middleware đặc biệt.
     *
     * @var array
     */
    protected $routeMiddleware = [
        /*  'auth' => \App\Http\Middleware\Authenticate::class, */
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        /*   'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, */
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'auth.jwt' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];

    protected $commands = [
        \App\Console\Commands\UpdateMovieStatus::class,
        \App\Console\Commands\UpdatePromotionStatus::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('movie:update-status')->daily(); // Chạy mỗi ngày
    }
}
