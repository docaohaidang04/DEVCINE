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
        'id_account',
        'id_combo',
        'id_payment',
        'booking_code',
        'booking_date',
        'quantity',
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
            'id_account' => 'required|exists:accounts,id_account',
            'id_combo' => 'nullable|exists:combos,id_combo',
            'id_payment' => 'nullable|exists:payment,id_payment',
            'quantity' => 'nullable|integer',
            'total_amount' => 'nullable|numeric',
            'payment_status' => 'nullable|string',
            'transaction_id' => 'nullable|string',
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
            'id_account' => 'sometimes|required|exists:accounts,id_account',
            'id_combo' => 'nullable|exists:combos,id_combo',
            'id_payment' => 'nullable|exists:payment,id_payment',
            'quantity' => 'nullable|integer',
            'total_amount' => 'nullable|numeric',
            'payment_status' => 'nullable|string',
            'transaction_id' => 'nullable|string',
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
            return response()->json(['message' => 'booking deleted successfully'], 200);
        }

        return response()->json(['message' => 'booking not found'], 404);
    }
}
