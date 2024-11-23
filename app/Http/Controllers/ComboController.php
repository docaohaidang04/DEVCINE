<?php

namespace App\Http\Controllers;

use App\Models\Combo;
use Illuminate\Http\Request;

class ComboController extends Controller
{
    // Lấy tất cả combos
    public function index()
    {
        $combos = Combo::getAllCombos(); // Gọi phương thức trong model
        return response()->json($combos);
    }

    // Tạo combo mới
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image_combos' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $destinationPath = public_path('combos');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Khởi tạo các biến để lưu tên tệp
        $imageCombos = null;

        // Lưu ảnh vào public/combos nếu có
        if ($request->hasFile('image_combos')) {
            $imageCombos = time() . '_' . $request->file('image_combos')->getClientOriginalName();
            $request->file('image_combos')->move($destinationPath, $imageCombos);
            $validatedData['image_combos'] = 'combos/' . $imageCombos;
        }

        $combo = Combo::createCombo($validatedData); // Gọi phương thức trong model
        return response()->json($combo, 201);
    }

    // Lấy một combo theo ID
    public function show($id)
    {
        $combo = Combo::getComboById($id); // Gọi phương thức trong model
        if (!$combo) {
            return response()->json(['message' => 'Combo not found'], 404);
        }
        return response()->json($combo);
    }

    // Cập nhật một combo theo ID
    public function update(Request $request, $id)
    {
        $combo = Combo::getComboById($id); // Gọi phương thức trong model
        if (!$combo) {
            return response()->json(['message' => 'Combo not found'], 404);
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

        $combo->updateCombo($validatedData); // Gọi phương thức trong model
        return response()->json($combo);
    }

    // Xóa một combo theo ID
    public function destroy($id)
    {
        $combo = Combo::getComboById($id); // Gọi phương thức trong model
        if (!$combo) {
            return response()->json(['message' => 'Combo not found'], 404);
        }

        // Xóa ảnh nếu có
        if ($combo->image_combos && file_exists(public_path($combo->image_combos))) {
            unlink(public_path($combo->image_combos));
        }

        $combo->deleteCombo(); // Gọi phương thức trong model
        return response()->json(['message' => 'Combo deleted']);
    }
}
