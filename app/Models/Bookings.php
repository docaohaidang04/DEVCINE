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
        'account_id',
        'account_promotion_id',
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

    // Mối quan hệ một-một với Account
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id_account');
    }

    // Mối quan hệ nhiều-nhiều với Products
    public function products()
    {
        return $this->belongsToMany(Product::class, 'booking_product', 'id_booking', 'id_product');
    }

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
            'account_id' => 'required|exists:accounts,id_account',
            'account_promotion_id' => 'nullable|exists:account_promotions,id_account_promotion',
            'id_product' => 'nullable|array',
            'id_product.*' => 'exists:products,id_product',
            'id_payment' => 'nullable|exists:payment,id_payment',
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

        // Lưu booking
        $booking = self::create([
            'account_id' => $data['account_id'], // Liên kết với tài khoản
            'account_promotion_id' => $data['account_promotion_id'] ?? null,
            'id_payment' => $data['id_payment'] ?? null,
            'id_ticket' => $data['id_ticket'] ?? null,
            'booking_code' => $data['booking_code'] ?? null,
            'total_amount' => $data['total_amount'] ?? 0,
            'payment_status' => $data['payment_status'] ?? null,
            'transaction_id' => $data['transaction_id'] ?? null,
            'payment_date' => $data['payment_date'] ?? null,
            'status' => $data['status'] ?? 'pending',
        ]);

        // Thêm sản phẩm vào booking nếu có
        if (isset($data['id_product']) && is_array($data['id_product'])) {
            $booking->products()->sync($data['id_product']);
        }

        return $booking;
    }

    // Cập nhật booking
    public function updateBooking($data)
    {
        $validator = Validator::make($data, [
            'account_id' => 'required|exists:accounts,id_account', // account_id là bắt buộc
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

        // Cập nhật thông tin booking
        $this->update($data);

        // Cập nhật lại sản phẩm
        if (isset($data['id_product']) && is_array($data['id_product'])) {
            $this->products()->sync($data['id_product']);
        }

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