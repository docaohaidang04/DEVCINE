<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationToken;
    protected $email;

    public function __construct($verificationToken, $email)
    {
        $this->verificationToken = $verificationToken;
        $this->email = $email;
    }

    public function build()
    {
        $verificationLink = env('APP_URL') . "/api/accounts-verify/$this->verificationToken";
        $subject = 'Xác Thực Tài Khoản';
        $content = "
        <html style='text-align: center; height: 100%; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center;'>
            <body style='font-family: Arial, sans-serif; color: #333; width: 100%; height: 100%; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center;'>
                <div style='background-color: #f4f4f4; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);'>
                    <h2 style='color: #2C3E50;'>Xác thực tài khoản</h2>
                    <p style='font-size: 16px;'>
                        Chào bạn,<br><br>
                        Cảm ơn bạn đã đăng ký tài khoản.<br>
                        Để hoàn tất quá trình đăng ký, vui lòng nhấn vào liên kết dưới đây để xác thực email của bạn:
                    </p>
                    <div style='margin: 20px 0;'>
                        <a href='$verificationLink' style='background-color: #3498db; color: white; padding: 12px 25px; text-decoration: none; font-size: 16px; border-radius: 5px;'>Xác thực Email</a>
                    </div>
                    <p style='font-size: 14px; color: #7f8c8d;'>
                        Nếu bạn không yêu cầu đăng ký tài khoản này, xin bỏ qua email này.<br><br>
                        Trân trọng,<br>
                        Đội ngũ hỗ trợ.
                    </p>
                </div>
            </body>
        </html>
        ";

        return $this->to($this->email)
            ->subject($subject)
            ->html($content);
    }
}
