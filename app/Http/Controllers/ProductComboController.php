<?php

namespace App\Http\Controllers;

use App\Models\ProductCombo;
use Illuminate\Http\Request;

class ProductComboController extends Controller
{
    // Lấy danh sách tất cả product combos
    public function index()
    {
        return response()->json(ProductCombo::getAllCombos());
    }

    // Lấy thông tin cụ thể của một product combo theo ID
    public function show($id)
    {
        return response()->json(ProductCombo::getComboById($id));
    }

    // Tạo mới product combo
    public function store(Request $request)
    {
        return response()->json(ProductCombo::createCombo($request->all()), 201);
    }

    // Cập nhật product combo
    public function update(Request $request, $id)
    {
        return response()->json(ProductCombo::updateCombo($id, $request->all()));
    }

    // Xóa product combo
    public function destroy($id)
    {
        return response()->json(ProductCombo::deleteCombo($id));
    }
}
