<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $data = $request->all();

        if ($request->hasFile('promotion_image')) {
            $data['promotion_image'] = $request->file('promotion_image');
        }

        $promotion = Promotion::createPromotion($data);

        // Nếu có lỗi, trả về thông báo lỗi
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
    public function update(Request $request, $id_promotion)
    {
        // Tìm promotion theo id_promotion
        $promotion = Promotion::findPromotion($id_promotion);

        if (!$promotion) {
            return response()->json(['error' => 'Promotion not found'], 404);
        }

        // In dữ liệu ra để kiểm tra
        Log::info($request->all());  // Ghi log dữ liệu nhận vào

        // Cập nhật promotion
        $updatedPromotion = $promotion->updatePromotion($request->all());

        // Nếu có lỗi trong quá trình cập nhật
        if (isset($updatedPromotion['errors'])) {
            return response()->json($updatedPromotion, 422);
        }

        return response()->json($updatedPromotion, 200);
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
            $user = Account::with(['accountPromotions' => function ($query) {
                // Lọc các bản ghi trong bảng 'account_promotion' có status = 'active'
                $query->where('status', 'active')
                    ->with('promotion'); // Sau khi lọc, kết nối với bảng 'promotion'
            }])->findOrFail($id);

            // Lấy thông tin khuyến mãi
            $promotions = $user->accountPromotions->map(function ($accountPromotion) {
                $promotion = $accountPromotion->promotion;

                // Kiểm tra xem promotion có tồn tại không
                if ($promotion) {
                    return [
                        'account_promotion_id' => $accountPromotion->account_promotion_id,
                        'id_promotion' => $promotion->id_promotion,
                        'promotion_name' => $promotion->promotion_name,
                        'description' => $promotion->description,
                        'promotion_image' => $promotion->promotion_image,
                        'min_purchase_amount' => $promotion->min_purchase_amount,
                        'max_discount_amount' => $promotion->max_discount_amount,
                        'promotion_point' => $promotion->promotion_point,
                        'discount_type' => $promotion->discount_type,
                        'discount_value' => $promotion->discount_value,
                        'start_date' => $promotion->start_date,
                        'end_date' => $promotion->end_date,
                        'created_at' => $promotion->created_at,
                        'updated_at' => $promotion->updated_at,
                        'status' => $accountPromotion->status,  // Lấy status từ bảng account_promotion
                    ];
                }

                return null; // Nếu không có promotion thì trả về null
            })->filter(); // Loại bỏ các null

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
