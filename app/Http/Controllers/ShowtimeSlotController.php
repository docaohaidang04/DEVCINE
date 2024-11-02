<?php

namespace App\Http\Controllers;

use App\Models\ShowtimeSlot;
use Illuminate\Http\Request;

class ShowtimeSlotController extends Controller
{
    // Lấy tất cả khung giờ chiếu
    public function index()
    {
        $slots = ShowtimeSlot::getAllSlots();
        return response()->json($slots, 200);
    }

    // Tạo khung giờ chiếu mới
    public function store(Request $request)
    {
        $request->validate([
            'slot_time' => 'required|date_format:H:i'
        ]);

        $slot = ShowtimeSlot::createSlot($request->all());
        return response()->json($slot, 201);
    }

    // Lấy thông tin khung giờ chiếu theo ID
    public function show($id)
    {
        $slot = ShowtimeSlot::getSlotById($id);

        if (!$slot) {
            return response()->json(['message' => 'Showtime slot not found'], 404);
        }

        return response()->json($slot, 200);
    }

    // Cập nhật khung giờ chiếu
    public function update(Request $request, $id)
    {
        $slot = ShowtimeSlot::getSlotById($id);

        if (!$slot) {
            return response()->json(['message' => 'Showtime slot not found'], 404);
        }

        $request->validate([
            'slot_time' => 'required|date_format:H:i'
        ]);

        $slot->updateSlot($request->all());
        return response()->json($slot, 200);
    }

    // Xóa khung giờ chiếu
    public function destroy($id)
    {
        $slot = ShowtimeSlot::getSlotById($id);

        if (!$slot) {
            return response()->json(['message' => 'Showtime slot not found'], 404);
        }

        $slot->deleteSlot();
        return response()->json(['message' => 'Showtime slot deleted'], 200);
    }
}
