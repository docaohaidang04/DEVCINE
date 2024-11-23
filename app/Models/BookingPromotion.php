<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class BookingPromotion extends Model
{
    use HasFactory;

    protected $table = 'booking_promotions';

    protected $primaryKey = 'id_promotion_ap';

    protected $fillable = [
        'id_promotion',
        'id_booking',
    ];

    // Lấy tất cả promotions
    public static function getAllPromotions()
    {
        return self::all();
    }

    // Lấy promotion theo id
    public static function getPromotionById($id)
    {
        return self::find($id);
    }

    // Tạo booking promotion mới
    public static function createBookingPromotion($data)
    {
        $validator = Validator::make($data, [
            'id_promotion' => 'required|exists:promotions,id_promotion',
            'id_booking' => 'required|exists:bookings,id_booking',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return self::create($data);
    }

    // Cập nhật booking promotion
    public function updateBookingPromotion($data)
    {
        $validator = Validator::make($data, [
            'id_promotion' => 'sometimes|required|exists:promotions,id_promotion',
            'id_booking' => 'sometimes|required|exists:bookings,id_booking',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $this->update($data);
        return $this;
    }

    // Xóa booking promotion
    public static function deleteBookingPromotion($id)
    {
        $promotion = self::find($id);
        if ($promotion) {
            $promotion->delete();
            return response()->json(['message' => 'promotion deleted successfully'], 200);
        }

        return response()->json(['message' => 'promotion not found'], 404);
    }
}
