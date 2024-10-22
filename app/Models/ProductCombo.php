<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCombo extends Model
{
    use HasFactory;

    protected $table = 'product_combos';
    protected $primaryKey = 'ID_PRODUCT_COMBO';

    protected $fillable = [
        'ID_COMBO',
        'ID_PRODUCT',
        'Quantity'
    ];

    public $timestamps = true;

    // Lấy tất cả product combos
    public static function getAllCombos()
    {
        return self::all(); // Hoặc thêm logic xử lý dữ liệu ở đây nếu cần
    }

    // Lấy product combo theo ID
    public static function getComboById($id)
    {
        return self::find($id);
    }

    // Tạo mới product combo
    public static function createCombo($data)
    {
        return self::create($data);
    }

    // Cập nhật product combo
    public static function updateCombo($id, $data)
    {
        $combo = self::find($id);
        if ($combo) {
            $combo->update($data);
            return $combo;
        }
        return null;
    }

    // Xóa product combo
    public static function deleteCombo($id)
    {
        $combo = self::find($id);
        if ($combo) {
            $combo->delete();
            return true;
        }
        return false;
    }
}
