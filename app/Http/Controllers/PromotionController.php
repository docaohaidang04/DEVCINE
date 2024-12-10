<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    // Lấy danh sách các promotion
    public function index()
    {
        return response()->json(Promotion::getAllPromotions());
    }

    // Tạo promotion mới
    public function store(Request $request)
    {
        // Gọi phương thức tạo promotion từ model
        $data = $request->all();

        if ($request->hasFile('promotion_image')) {
            $data['promotion_image'] = $request->file('promotion_image');
        }

        $promotion = Promotion::createPromotion($data);

        if (isset($promotion['errors'])) {
            return response()->json($promotion, 422);
        }

        return response()->json($promotion, 201);
    }

    // Lấy thông tin của một promotion cụ thể
    public function show($id)
    {
        $promotion = Promotion::findPromotion($id);

        if (!$promotion) {
            return response()->json(['error' => 'Promotion not found'], 404);
        }

        return response()->json($promotion);
    }

    // Cập nhật promotion
    public function update(Request $request, $id)
    {
        $promotion = Promotion::findPromotion($id);

        if (!$promotion) {
            return response()->json(['error' => 'Promotion not found'], 404);
        }

        $updatedPromotion = $promotion->updatePromotion($request->all());

        if (isset($updatedPromotion['errors'])) {
            return response()->json($updatedPromotion, 422);
        }

        return response()->json($promotion, 200);
    }

    // Xóa promotion
    public function destroy($id)
    {
        $promotion = Promotion::findPromotion($id);

        if (!$promotion) {
            return response()->json(['error' => 'Promotion not found'], 404);
        }

        $promotion->deletePromotion();

        return response()->json(null, 204);
    }
}
