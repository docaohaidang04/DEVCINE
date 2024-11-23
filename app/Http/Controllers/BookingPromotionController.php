<?php

namespace App\Http\Controllers;

use App\Models\BookingPromotion;
use Illuminate\Http\Request;

class BookingPromotionController extends Controller
{
    // Lấy tất cả promotions
    public function index()
    {
        $promotions = BookingPromotion::getAllPromotions();
        return response()->json($promotions);
    }

    // Lấy promotion theo id
    public function show($id)
    {
        $promotion = BookingPromotion::getPromotionById($id);
        if ($promotion) {
            return response()->json($promotion);
        }
        return response()->json(['message' => 'promotion not found'], 404);
    }

    // Tạo booking promotion mới
    public function store(Request $request)
    {
        $promotion = BookingPromotion::createBookingPromotion($request->all());
        if ($promotion instanceof \Illuminate\Http\JsonResponse) {
            return $promotion; // Trả về lỗi nếu có
        }

        return response()->json($promotion, 201);
    }

    // Cập nhật booking promotion
    public function update(Request $request, $id)
    {
        $promotion = BookingPromotion::getPromotionById($id);
        if (!$promotion) {
            return response()->json(['message' => 'promotion not found'], 404);
        }

        $updatedPromotion = $promotion->updateBookingPromotion($request->all());
        if ($updatedPromotion instanceof \Illuminate\Http\JsonResponse) {
            return $updatedPromotion; // Trả về lỗi nếu có
        }

        return response()->json($updatedPromotion);
    }

    // Xóa booking promotion
    public function destroy($id)
    {
        return BookingPromotion::deleteBookingPromotion($id);
    }
}
