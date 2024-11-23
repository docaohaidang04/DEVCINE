<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class Combo extends Model
{
    use HasFactory;

    protected $table = 'combos';

    protected $primaryKey = 'id_combo';

    protected $fillable = [
        'name',
        'description',
        'image_combos',
        'created_at',
        'updated_at'
    ];

    // Lấy tất cả combos
    public static function getAllCombos()
    {
        return self::all();
    }

    // Tạo combo mới
    public static function createCombo($request)
    {
        // Validate dữ liệu
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image_combos' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Lưu ảnh vào public/combos nếu có
        $destinationPath = public_path('combos');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $imageCombos = null;

        if ($request->hasFile('image_combos')) {
            $imageCombos = time() . '_' . $request->file('image_combos')->getClientOriginalName();
            $request->file('image_combos')->move($destinationPath, $imageCombos);
            $validatedData['image_combos'] = 'combos/' . $imageCombos;
        }

        return self::create($validatedData);
    }

    // Lấy combo theo ID
    public static function getComboById($id)
    {
        return self::find($id);
    }

    // Cập nhật combo
    public static function updateCombo($id, $request)
    {
        $combo = self::find($id);
        if (!$combo) {
            return null; // Nếu không tìm thấy combo, trả về null
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string|max:1000',
            'image_combos' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Cập nhật ảnh nếu có
        if ($request->hasFile('image_combos')) {
            $destinationPath = public_path('combos');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $imageCombos = time() . '_' . $request->file('image_combos')->getClientOriginalName();
            $request->file('image_combos')->move($destinationPath, $imageCombos);
            $validatedData['image_combos'] = 'combos/' . $imageCombos;

            // Xóa ảnh cũ nếu có
            if ($combo->image_combos && file_exists(public_path($combo->image_combos))) {
                unlink(public_path($combo->image_combos));
            }
        }

        $combo->update($validatedData);
        return $combo;
    }

    // Xóa combo
    public function deleteCombo()
    {
        // Xóa ảnh nếu có
        if ($this->image_combos && file_exists(public_path($this->image_combos))) {
            unlink(public_path($this->image_combos));
        }

        return $this->delete();
    }
}
