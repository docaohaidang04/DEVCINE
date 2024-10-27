<?php

namespace App\Http\Controllers;

use App\Models\ProductCombo;
use Illuminate\Http\Request;

class ProductComboController extends Controller
{
    // Lấy danh sách tất cả product combos
    public function index()
    {
        $productCombos = ProductCombo::getAllCombos();
        return response()->json($productCombos);
    }

    // Lấy thông tin cụ thể của một product combo theo ID
    public function show($id)
    {
        $productCombo = ProductCombo::getComboById($id);

        if ($productCombo) {
            return response()->json($productCombo);
        } else {
            return response()->json(['message' => 'Product combo not found'], 404);
        }
    }

    // Tạo mới product combo
    public function store(Request $request)
    {
        $request->validate([
            'id_combo' => 'required|exists:combos,id_combo',
            'id_product' => 'required|exists:products,id_product',
            'quantity' => 'required|integer|min:1',
        ]);

        $productCombo = ProductCombo::createCombo($request->all());

        return response()->json($productCombo, 201);
    }

    // Cập nhật product combo
    public function update(Request $request, $id)
    {
        // Validate dữ liệu
        $request->validate([
            'id_combo' => 'exists:combos,id_combo',
            'id_product' => 'exists:products,id_product',
            'quantity' => 'integer|min:1',
        ]);

        $productCombo = ProductCombo::updateCombo($id, $request->all());

        if ($productCombo) {
            return response()->json($productCombo, 200);
        } else {
            return response()->json(['message' => 'Product combo not found'], 404);
        }
    }

    // Xóa product combo
    public function destroy($id)
    {
        $deleted = ProductCombo::deleteCombo($id);

        if ($deleted) {
            return response()->json(null, 204);
        } else {
            return response()->json(['message' => 'Product combo not found'], 404);
        }
    }
}
