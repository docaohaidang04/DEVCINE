<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class ProductCombo extends Model
{
    use HasFactory;

    protected $table = 'product_combos';
    protected $primaryKey = 'id_product_combo';

    protected $fillable = [
        'id_combo',
        'id_product',
        'quantity'
    ];

    public $timestamps = true;

    // Lấy tất cả product combos
    public static function getAllCombos()
    {
        return self::all();
    }

    // Lấy thông tin cụ thể của product combo theo ID
    public static function getComboById($id)
    {
        $combo = self::find($id);
        if ($combo) {
            return $combo;
        }
        return response()->json(['message' => 'Product combo not found'], 404);
    }

    // Tạo mới product combo với validation
    public static function createCombo($data)
    {
        // Validate dữ liệu
        $validator = Validator::make($data, [
            'id_combo' => 'required|exists:combos,id_combo',
            'id_product' => 'required|exists:products,id_product',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return self::create($data);
    }

    // Cập nhật product combo với validation
    public static function updateCombo($id, $data)
    {
        // Validate dữ liệu
        $validator = Validator::make($data, [
            'id_combo' => 'exists:combos,id_combo',
            'id_product' => 'exists:products,id_product',
            'quantity' => 'integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $combo = self::find($id);
        if ($combo) {
            $combo->update($data);
            return $combo;
        }
        return response()->json(['message' => 'Product combo not found'], 404);
    }

    // Xóa product combo
    public static function deleteCombo($id)
    {
        $combo = self::find($id);
        if ($combo) {
            $combo->delete();
            return response()->json(['message' => 'Product combo deleted successfully'], 200);
        }
        return response()->json(['message' => 'Product combo not found'], 404);
    }
}
