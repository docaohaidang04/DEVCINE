<?php

namespace App\Http\Controllers;

use App\Models\Account;
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
    public function getPromotionByIdAccount($id)
    {
        try {
            // Tìm tài khoản theo ID và bao gồm quan hệ với bảng 'accountPromotions' và 'promotion'
            $user = Account::with('accountPromotions.promotion')->findOrFail($id);

            // Lấy thông tin khuyến mãi, bao gồm cả 'account_promotion_id'
            $promotions = $user->accountPromotions->map(function ($accountPromotion) {
                return [
                    'account_promotion_id' => $accountPromotion->account_promotion_id,  // Thêm account_promotion_id
                    'id_promotion' => $accountPromotion->promotion->id_promotion,  // ID khuyến mãi
                    'promotion_name' => $accountPromotion->promotion->promotion_name,  // Tên khuyến mãi
                    'description' => $accountPromotion->promotion->description,  // Mô tả khuyến mãi
                    'promotion_image' => $accountPromotion->promotion->promotion_image,  // Hình ảnh khuyến mãi
                    'min_purchase_amount' => $accountPromotion->promotion->min_purchase_amount,  // Mức mua tối thiểu
                    'max_discount_amount' => $accountPromotion->promotion->max_discount_amount,  // Giảm giá tối đa
                    'promotion_point' => $accountPromotion->promotion->promotion_point,  // Điểm thưởng
                    'discount_type' => $accountPromotion->promotion->discount_type,  // Loại giảm giá
                    'discount_value' => $accountPromotion->promotion->discount_value,  // Giá trị giảm giá
                    'start_date' => $accountPromotion->promotion->start_date,  // Ngày bắt đầu
                    'end_date' => $accountPromotion->promotion->end_date,  // Ngày kết thúc
                    'created_at' => $accountPromotion->promotion->created_at,  // Thời gian tạo
                    'updated_at' => $accountPromotion->promotion->updated_at,  // Thời gian cập nhật
                ];
            });

            return response()->json([
                'status' => true,
                'data' => $promotions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Khách hàng không tồn tại hoặc có lỗi: ' . $e->getMessage()
            ], 404);
        }
    }
}
