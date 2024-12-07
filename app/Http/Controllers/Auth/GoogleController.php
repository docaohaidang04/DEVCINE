<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Account;
use Illuminate\Support\Facades\Http;
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


    // public function loginCallback()
    // {
    //     try {
    //         $googleUser = Socialite::driver('google')
    //             ->stateless()
    //             ->user();

    //         $account = Account::where('email', $googleUser->getEmail())->first();
    //         if ($account) {
    //             throw new \Exception('Email đã được đăng ký trước đó.');
    //         }

    //         $account = Account::create([
    //             'email' => $googleUser->getEmail(),
    //             'user_name' => $googleUser->getName(),
    //             'google_id' => $googleUser->getId(),
    //             'avatar' => $googleUser->getAvatar(),
    //             'password' => bcrypt('google_default_password'),
    //             'role' => 'user',
    //         ]);

    //         return response()->json([
    //             'status' => 'Đăng nhập Google thành công',
    //             'data' => $account,
    //         ], Response::HTTP_CREATED);
    //     } catch (\Exception $exception) {
    //         return response()->json([
    //             'status' => 'Đăng nhập Google thất bại',
    //             'error' => $exception->getMessage(),
    //         ], Response::HTTP_BAD_REQUEST);
    //     }
    // }
    public function loginCallback(Request $request)
    {
        try {
            // Lấy mã code từ query
            $code = $request->query('code');
            if (!$code) {
                throw new \Exception('Mã code không hợp lệ.');
            }

            // Gửi mã code đến Google để lấy token
            $response = Http::post('https://oauth2.googleapis.com/token', [
                'code' => $code,
                'client_id' => env('GOOGLE_CLIENT_ID'), // Lấy từ file .env
                'client_secret' => env('GOOGLE_CLIENT_SECRET'),
                'redirect_uri' => env('GOOGLE_REDIRECT_URI'),
                'grant_type' => 'authorization_code',
            ]);

            if ($response->failed()) {
                throw new \Exception('Không thể lấy token từ Google.');
            }

            // Lấy token và user info
            $tokens = $response->json();
            $accessToken = $tokens['access_token'];

            // Lấy thông tin người dùng
            $googleUser = Http::withToken($accessToken)
                ->get('https://www.googleapis.com/oauth2/v3/userinfo')
                ->json();

            // Xử lý logic lưu tài khoản
            $account = Account::firstOrCreate(
                ['email' => $googleUser['email']],
                [
                    'user_name' => $googleUser['email'],
                    'full_name' => $googleUser['name'],
                    'google_id' => $googleUser['sub'], // ID Google
                    'avatar' => $googleUser['picture'],
                    'password' => bcrypt('google_default_password'), // Đặt mật khẩu mặc định
                    'role' => 'user',
                ]
            );

            return response()->json([
                'status' => 'Đăng nhập Google thành công',
                'data' => $account,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Đăng nhập Google thất bại',
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
