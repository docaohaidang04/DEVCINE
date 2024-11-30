<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:accounts,email']);

        $token = Str::random(64);
        $expiresAt = Carbon::now()->addMinutes(60);

        // Cập nhật token và thời gian hết hạn vào database
        $account = Account::where('email', $request->email)->first();
        $account->reset_token = $token;
        $account->reset_token_expires_at = $expiresAt;
        $account->save();

        // Gửi email
        $resetLink = env('APP_URL') . "/password-reset?token=$token";
        Mail::html(
            "
            <html style='text-align: center; height: 100%; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center;'>
                <body style='font-family: Arial, sans-serif; color: #333; width: 100%; height: 100%; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center;'>
                    <div style='background-color: #f4f4f4; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);'>
                        <h2 style='color: #2C3E50;'>Yêu cầu đặt lại mật khẩu</h2>
                        <p style='font-size: 16px;'>
                            Chào bạn,<br><br>
                            Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình.<br>
                            Để tiếp tục, vui lòng nhấn vào liên kết dưới đây trong vòng 1 giờ:
                        </p>
                        <div style='margin: 20px 0;'>
                            <a href='$resetLink' style='background-color: #3498db; color: white; padding: 12px 25px; text-decoration: none; font-size: 16px; border-radius: 5px;'>Đặt lại mật khẩu</a>
                        </div>
                        <p style='font-size: 14px; color: #7f8c8d;'>
                            Nếu bạn không yêu cầu thay đổi mật khẩu, xin bỏ qua email này.<br><br>
                            Trân trọng,<br>
                            Đội ngũ hỗ trợ.
                        </p>
                    </div>
                </body>
            </html>
            ",
            function (Message $message) use ($request) {
                $message->to($request->email)
                    ->subject('Yêu cầu đặt lại mật khẩu');
            }
        );

        return response()->json(['message' => 'Email đặt lại mật khẩu đã được gửi thành công.'], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $account = Account::where('reset_token', $request->token)
            ->where('reset_token_expires_at', '>', Carbon::now())
            ->first();

        if (!$account) {
            return response()->json(['message' => 'Token không hợp lệ hoặc đã hết hạn.'], 400);
        }

        // Đặt lại mật khẩu
        $account->password = Hash::make($request->password);
        $account->reset_token = null;
        $account->reset_token_expires_at = null;
        $account->save();

        return response()->json(['message' => 'Mật khẩu đã được đặt lại thành công.'], 200);
    }
}
