<?php

namespace App\Http\Controllers;

use App\Models\Chair;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChairController extends Controller
{
    // Lấy tất cả chairs
    public function index(): JsonResponse
    {
        return response()->json(Chair::getAllChairs());
    }

    // Lấy chairs theo id_room
    public function getChairsByRoom($id_room): JsonResponse
    {
        $chairs = Chair::where('id_room', $id_room)->get();
        return response()->json($chairs);
    }

    // Tạo mới chair
    public function store(Request $request): JsonResponse
    {
        $chair = Chair::createChair($request->all());
        if ($chair instanceof \Illuminate\Http\JsonResponse) {
            return $chair; // Trả về lỗi nếu có
        }

        return response()->json($chair, 201);
    }

    // Lấy chair theo id
    public function show($id): JsonResponse
    {
        $chair = Chair::getChairById($id);
        return response()->json($chair);
    }

    // Cập nhật chair
    public function update(Request $request, $id): JsonResponse
    {
        $chair = Chair::updateChair($id, $request->all());
        if ($chair instanceof \Illuminate\Http\JsonResponse) {
            return $chair; // Trả về lỗi nếu có
        }

        return response()->json($chair);
    }

    // Xóa chair
    public function destroy($id): JsonResponse
    {
        Chair::deleteChair($id);
        return response()->json(null, 204);
    }
}
