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
        ]);

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
            'description' => 'sometimes|nullable|string|max:1000'
        ]);

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

        $combo->deleteCombo(); // Gọi phương thức trong model
        return response()->json(['message' => 'Combo deleted']);
    }
}
