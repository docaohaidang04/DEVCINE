<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Account;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;

class GoogleController extends Controller
{
    public function getGoogleSignInUrl()
    {
        try {
            if (!session()->has('state')) {
                // Khởi tạo session nếu cần thiết
                session()->put('state', bin2hex(random_bytes(16)));
            }

            $url = Socialite::driver('google')->redirect()->getTargetUrl();

            return response()->json([
                'url' => $url,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'Lỗi khi lấy URL đăng nhập Google',
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function loginCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            $account = Account::where('email', $googleUser->getEmail())->first();
            if ($account) {
                throw new \Exception('Email đã được đăng ký trước đó.');
            }

            $account = Account::create([
                'email' => $googleUser->getEmail(),
                'user_name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => bcrypt('google_default_password'),
                'role' => 'user',
            ]);

            return response()->json([
                'status' => 'Đăng nhập Google thành công',
                'data' => $account,
            ], Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'Đăng nhập Google thất bại',
                'error' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
