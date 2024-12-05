<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('user_name', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['status' => 400, 'message' => 'Tài khoản hoặc mật khẩu không đúng'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        $user = Auth::user();

        if ($user->email_verified_at === null) {
            return response()->json(['message' => 'Vui lòng xác thực email của bạn.'], 401);
        }

        // Tạo refresh token với thời gian hết hạn 30 ngày
        $refreshToken = JWTAuth::fromUser($user, ['exp' => Carbon::now()->addDays(30)->timestamp]);

        // Trả về thông tin tài khoản cùng với access_token và refresh_token
        return response()->json([
            'status' => 200,
            'message' => 'Đăng nhập thành công',
            'user' => $user,
            'access_token' => $token,
            'refresh_token' => $refreshToken,
        ]);
    }
}