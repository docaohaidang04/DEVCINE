<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bookingDetails;

    /**
     * Tạo mới instance mail.
     */
    public function __construct($bookingDetails)
    {
        $this->bookingDetails = $bookingDetails;
    }

    /**
     * Xây dựng nội dung email.
     */
    public function build()
    {
        $booking = $this->bookingDetails;

        $subject = 'Xác nhận đặt vé';
        $showDate = \Carbon\Carbon::parse($booking->ticket->showtime->date_time)->format('d/m/Y');

        // Lấy danh sách ghế
        $chairs = $booking->ticket->chairs->pluck('chair_name')->join(', ');

        // Lấy danh sách sản phẩm
        $products = '';
        foreach ($booking->products as $product) {
            $products .= "<li style='padding: 5px 0;'>- {$product->product_name} : {$product->price} VNĐ</li>";
        }

        $content = "
    <html style='text-align: center; height: 100%; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center;'>
        <body style='font-family: Arial, sans-serif; color: #333; width: 100%; height: 100%; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center;'>
            <div style='background-color: #f4f4f4; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);'>
                <h2 style='color: #2C3E50;'>Xác nhận đặt vé</h2>
                <p style='font-size: 16px;'>
                    Chào bạn,<br><br>
                    Cảm ơn bạn đã đặt vé. Đây là thông tin chi tiết về vé của bạn:
                </p>
                <p style='font-size: 16px;'>
                    <strong>Mã đặt vé:</strong> {$booking->booking_code}<br>
                    <strong>Ngày đặt:</strong> {$booking->booking_date}<br>
                    <strong>Tổng số tiền:</strong> {$booking->total_amount} VNĐ<br>
                </p>

               <strong style='font-size: 16px;'>Sản phẩm:</strong>
                <ul style='list-style-type: none; padding: 0; style='font-size: 16px;'>
                    {$products}
                </ul>

                <p style='font-size: 16px;'>
                    <strong>Ngày chiếu:</strong> {$showDate}<br>
                    <strong>Giờ chiếu:</strong> {$booking->ticket->showtime->slot_time}<br>
                    <strong>Phim:</strong> {$booking->ticket->showtime->movie->movie_name}<br>
                    <strong>Ghế ngồi:</strong> {$chairs}
                </p>

                <p style='font-size: 14px; color: #7f8c8d;'>
                    Nếu bạn không yêu cầu đặt vé này, xin bỏ qua email này.<br><br>
                    Trân trọng,<br>
                    Đội ngũ hỗ trợ.
                </p>
            </div>
        </body>
    </html>";

        return $this->to($booking->account->email)
            ->subject($subject)
            ->html($content);
    }
}
