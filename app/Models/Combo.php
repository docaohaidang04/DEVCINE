<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    use HasFactory;

    protected $table = 'COMBOS';
    protected $primaryKey = 'ID_COMBO';

    protected $fillable = [
        'name',
        'Created_at',
        'Update_at'
    ];

    // Lấy tất cả combos
    public static function getAllCombos()
    {
        return self::all(); // Trả về tất cả combo
    }

    // Tạo combo mới
    public static function createCombo($data)
    {
        return self::create($data); // Tạo combo mới với dữ liệu
    }

    // Lấy một combo theo ID
    public static function getComboById($id)
    {
        return self::find($id); // Tìm combo theo ID
    }

    // Cập nhật một combo theo ID
    public function updateCombo($data)
    {
        return $this->update($data); // Cập nhật combo với dữ liệu
    }

    // Xóa một combo theo ID
    public function deleteCombo()
    {
        return $this->delete(); // Xóa combo
    }
}
