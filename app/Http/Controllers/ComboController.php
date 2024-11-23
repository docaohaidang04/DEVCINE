<?php

namespace App\Http\Controllers;

use App\Models\Combo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ComboController extends Controller
{
    // Lấy tất cả combos
    public function index(): JsonResponse
    {
        return response()->json(Combo::getAllCombos());
    }

    // Tạo combo mới
    public function store(Request $request): JsonResponse
    {
        $combo = Combo::createCombo($request); // Gọi phương thức trong model
        if ($combo instanceof \Illuminate\Http\JsonResponse) {
            return $combo; // Trả về lỗi nếu có
        }

        return response()->json($combo, 201);
    }

    // Lấy một combo theo ID
    public function show($id): JsonResponse
    {
        $combo = Combo::getComboById($id);
        if (!$combo) {
            return response()->json(['message' => 'Combo not found'], 404);
        }
        return response()->json($combo);
    }

    // Cập nhật một combo theo ID
    public function update(Request $request, $id): JsonResponse
    {
        $combo = Combo::updateCombo($id, $request); // Gọi phương thức trong model
        if (!$combo) {
            return response()->json(['message' => 'Combo not found'], 404);
        }
        return response()->json($combo);
    }

    // Xóa một combo theo ID
    public function destroy($id): JsonResponse
    {
        $combo = Combo::getComboById($id);
        if (!$combo) {
            return response()->json(['message' => 'Combo not found'], 404);
        }
        $combo->deleteCombo(); // Gọi phương thức trong model
        return response()->json(['message' => 'Combo deleted']);
    }
}
