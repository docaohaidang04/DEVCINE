<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    /**
     * Quan hệ với bảng account_promotion
     */
    public function accounts()
    {
        return $this->belongsToMany(Account::class, 'account_promotion', 'promotion_id', 'account_id', 'id_promotion', 'id_account');
    }

    /**
     * Lấy tất cả các promotion
     */
    public static function getAllPromotions()
    {
        return self::all();
    }

    /**
     * Tạo promotion mới
     */
    public static function createPromotion(array $data)
    {
        $validator = Validator::make($data, [
            'promotion_name' => 'required|string|max:255',
            'description' => 'required|string',
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
            return ['errors' => $validator->errors()];
        }

        // Xử lý hình ảnh nếu có
        if (isset($data['promotion_image'])) {
            $imagePath = self::handleImageUpload($data['promotion_image']);
            $data['promotion_image'] = $imagePath;
        }

        return self::create($data);
    }

    /**
     * Cập nhật promotion
     */
    public static function updatePromotion($request, $id_promotion)
    {
        // Validate dữ liệu
        $request->validate([
            'promotion_name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'discount_type' => 'sometimes|required|string',
            'discount_value' => 'sometimes|required|numeric',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date',
            'min_purchase_amount' => 'sometimes|required|numeric',
            'max_discount_amount' => 'sometimes|required|numeric',
            'promotion_point' => 'sometimes|required|numeric',
            'promotion_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Tìm promotion cần cập nhật
        $promotion = self::findOrFail($id_promotion);

        // Xử lý cập nhật ảnh
        if ($request->hasFile('promotion_image')) {
            self::deleteImageIfExists($promotion->promotion_image);
            $promotion->promotion_image = 'promotions/' . self::uploadImage($request->file('promotion_image'), public_path('promotions'));
        }

        // Cập nhật thông tin promotion
        $promotion->update($request->only([
            'promotion_name',
            'description',
            'discount_type',
            'discount_value',
            'start_date',
            'end_date',
            'min_purchase_amount',
            'max_discount_amount',
            'promotion_point'
        ]));

        return $promotion;
    }

    /**
     * Xử lý tải lên hình ảnh
     */
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

    /**
     * Xóa hình ảnh nếu tồn tại
     */
    protected static function deleteImageIfExists($imagePath)
    {
        if ($imagePath && File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));
        }
    }

    /**
     * Xóa promotion
     */
    public function deletePromotion(): bool
    {
        self::deleteImageIfExists($this->promotion_image);
        return $this->delete();
    }

    /**
     * Tìm promotion theo id
     */
    public static function findPromotion($id)
    {
        return self::find($id);
    }

    public function accountPromotions()
    {
        return $this->hasMany(AccountPromotion::class, 'promotion_id', 'id_promotion');
    }
}
