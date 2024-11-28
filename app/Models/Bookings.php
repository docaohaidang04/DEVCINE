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
            'id_product' => 'nullable|array', // id_product là mảng
            'id_product.*' => 'exists:products,id_product', // Kiểm tra từng phần tử trong mảng
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

        // Chuyển id_product thành JSON nếu là mảng
        $id_product = isset($data['id_product']) && is_array($data['id_product']) ? json_encode($data['id_product']) : null;

        // Lưu booking với id_product là mảng (dưới dạng JSON)
        return self::create([
            'account_promotion_id' => $data['account_promotion_id'] ?? null,
            'id_product' => $id_product, // Lưu id_product dưới dạng JSON
            'id_payment' => $data['id_payment'] ?? null,
            'id_ticket' => $data['id_ticket'] ?? null,
            'booking_code' => $data['booking_code'] ?? null,
            'total_amount' => $data['total_amount'] ?? 0,
            'payment_status' => $data['payment_status'] ?? null,
            'transaction_id' => $data['transaction_id'] ?? null,
            'payment_date' => $data['payment_date'] ?? null,
            'status' => $data['status'] ?? 'pending',
        ]);
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
