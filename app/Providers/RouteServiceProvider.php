<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Đây là namespace mặc định cho các route.
     *
     * @var string
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Đăng ký bất kỳ route nào trong ứng dụng.
     *
     * @return void
     */
    public function boot()
    {
        // Đăng ký các route
        Route::middleware('api')
            ->prefix('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Đăng ký các route trong ứng dụng.
     *
     * @return void
     */
    public function map()
    {
        // Chỉ cần đảm bảo gọi phương thức này để nạp các route API
        $this->boot();
    }
}
