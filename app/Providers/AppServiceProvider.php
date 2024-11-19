<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as LaravelAuthServiceProvider; // Đổi tên alias

class AuthServiceProvider extends LaravelAuthServiceProvider
{
    /**
     * Đăng ký bất kỳ dịch vụ nào cho ứng dụng.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Thực hiện các dịch vụ khởi tạo.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies(); // Gọi phương thức registerPolicies() tại đây
    }
}
