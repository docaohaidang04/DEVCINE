<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Bookings extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $primaryKey = 'id_booking';

    protected $fillable = [
        'account_promotion_id',
        'id_product',
        'id_payment',
        'id_ticket',
        'booking_code',
        'booking_date',
        'total_amount',
        'payment_status',
        'transaction_id',
        'payment_date',
        'status',
    ];

    // Lấy tất cả bookings
    public static function getAllBookings()
    {
        return self::all();
    }

    // Lấy booking theo id
    public static function getBookingById($id)
    {
        return self::find($id);
    }

    // Tạo booking mới
    public static function createBooking($data)
    {
        $validator = Validator::make($data, [
            'account_promotion_id' => 'nullable|exists:account_promotions,id_account_promotion',
            'id_product' => 'nullable|exists:products,id_product',
            'id_payment' => 'nullable|exists:payments,id_payment',
            'id_ticket' => 'nullable|exists:tickets,id_ticket',
            'booking_code' => 'nullable|string|max:255',
            'total_amount' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|string',
            'transaction_id' => 'nullable|string|max:255',
            'payment_date' => 'nullable|date',
            'status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return self::create($data);
    }

    // Cập nhật booking
    public function updateBooking($data)
    {
        $validator = Validator::make($data, [
            'account_promotion_id' => 'nullable|exists:account_promotions,id_account_promotion',
            'id_product' => 'nullable|exists:products,id_product',
            'id_payment' => 'nullable|exists:payments,id_payment',
            'id_ticket' => 'nullable|exists:tickets,id_ticket',
            'booking_code' => 'nullable|string|max:255',
            'total_amount' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|string',
            'transaction_id' => 'nullable|string|max:255',
            'payment_date' => 'nullable|date',
            'status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $this->update($data);
        return $this;
    }

    // Xóa booking
    public static function deleteBooking($id)
    {
        $booking = self::find($id);
        if ($booking) {
            $booking->delete();
            return response()->json(['message' => 'Booking deleted successfully'], 200);
        }

        return response()->json(['message' => 'Booking not found'], 404);
    }
}
