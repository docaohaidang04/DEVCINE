<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

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
        'promotion_point',
        'promotion_image'
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
        $validator = Validator::make($data, [
            'promotion_name' => 'required|string|max:255',
            'discount_type' => 'required|string',
            'discount_value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'min_purchase_amount' => 'required|numeric',
            'max_discount_amount' => 'required|numeric',
            'promotion_point' => 'required|numeric',
            'promotion_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (isset($data['promotion_image'])) {
            $imagePath = self::handleImageUpload($data['promotion_image']);
            $data['promotion_image'] = $imagePath;
        }

        return self::create($data);
    }

    // Cập nhật promotion với ảnh
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
            'promotion_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate ảnh
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Xử lý ảnh mới nếu có
        if (isset($data['promotion_image'])) {
            // Xóa ảnh cũ nếu có
            if ($this->promotion_image && File::exists(public_path($this->promotion_image))) {
                File::delete(public_path($this->promotion_image));
            }

            $imagePath = self::handleImageUpload($data['promotion_image']);
            $data['promotion_image'] = $imagePath;
        }

        return $this->update($data);
    }

    // Xử lý tải lên ảnh
    private static function handleImageUpload($image)
    {
        $destinationPath = public_path('promotions');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move($destinationPath, $imageName);

        return 'promotions/' . $imageName;
    }


    // Xóa promotion
    public function deletePromotion(): bool
    {
        return $this->delete();
    }
}
