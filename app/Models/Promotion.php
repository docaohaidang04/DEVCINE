<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;

class Promotion extends Model
{
    use HasFactory;

    protected $table = 'promotions';

    protected $primaryKey = 'id_promotion';

    protected $fillable = [
        'promotion_name',
        'description',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'min_purchase_amount',
        'max_discount_amount',
        'promotion_point'
    ];

    public $timestamps = true;

    public function accounts()
    {
        return $this->belongsToMany(Account::class, 'account_promotion', 'promotion_id', 'account_id');
    }

    // Lấy danh sách tất cả promotion
    public static function getAllPromotions(): Collection
    {
        return self::all();
    }

    // Tạo promotion mới với validation
    public static function createPromotion(array $data)
    {
        // Validate dữ liệu
        $validator = Validator::make($data, [
            'promotion_name' => 'required|string|max:255',
            'discount_type' => 'required|string',
            'discount_value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'min_purchase_amount' => 'required|numeric',
            'max_discount_amount' => 'required|numeric',
            'promotion_point' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return self::create($data);
    }

    // Tìm promotion theo ID
    public static function findPromotion($id): Promotion
    {
        return self::findOrFail($id);
    }

    // Cập nhật promotion với validation
    public function updatePromotion(array $data)
    {
        // Validate dữ liệu
        $validator = Validator::make($data, [
            'promotion_name' => 'required|string|max:255',
            'discount_type' => 'required|string',
            'discount_value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'min_purchase_amount' => 'required|numeric',
            'max_discount_amount' => 'required|numeric',
            'promotion_point' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return $this->update($data);
    }

    // Xóa promotion
    public function deletePromotion(): bool
    {
        return $this->delete();
    }
}
