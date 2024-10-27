<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    // Lấy danh sách các promotion
    public function index()
    {
        return Promotion::getAllPromotions();
    }

    // Tạo mới một promotion
    public function store(Request $request)
    {
        $request->validate([
            'promotion_name' => 'required|string|max:255',
            'discount_type' => 'required|string',
            'discount_value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'min_purchase_amount' => 'required|numeric',
            'max_discount_amount' => 'required|numeric',
        ]);

        $promotion = Promotion::createPromotion($request->all());

        return response()->json($promotion, 201);
    }

    // Lấy thông tin của một promotion cụ thể
    public function show($id)
    {
        return Promotion::findPromotion($id);
    }

    // Cập nhật promotion
    public function update(Request $request, $id)
    {
        $promotion = Promotion::findPromotion($id);
        $promotion->updatePromotion($request->all());

        return response()->json($promotion, 200);
    }

    // Xóa promotion
    public function destroy($id)
    {
        $promotion = Promotion::findPromotion($id);
        $promotion->deletePromotion();

        return response()->json(null, 204);
    }
}
