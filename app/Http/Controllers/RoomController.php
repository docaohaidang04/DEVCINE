<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Chair;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::getAllRooms();
        return response()->json($rooms, 200);
    }

    public function show($id)
    {
        $room = Room::getRoomById($id);
        if ($room) {
            return response()->json($room, 200);
        }
        return response()->json(['message' => 'Room not found'], 404);
    }

    public function store(Request $request)
{
    try {
        $request->validate([
            'room_name' => 'required|string|max:255',
            'room_status' => 'nullable|string',
            'room_type' => 'nullable|string',
            'chair_number' => 'nullable|integer|min:1', // Số ghế phải là số nguyên dương
        ]);

        // Tạo phòng
        $room = Room::create($request->only(['room_name', 'room_status', 'room_type', 'chair_number']));

        // Tự động tạo ghế nếu có chair_number
        if ($request->filled('chair_number')) {
            $chairNumber = $request->input('chair_number');
            $this->generateChairs($room->id_room, $chairNumber);
        }

        return response()->json($room, 201);
    } catch (\Exception $e) {
        Log::error('Room creation failed: ' . $e->getMessage());
        return response()->json(['error' => 'Room creation failed: ' . $e->getMessage()], 400);
    }
}

/**
 * Hàm tự động sinh ghế
 */
private function generateChairs($idRoom, $chairNumber)
{
    $rows = range('A', 'Z'); // Tạo danh sách hàng từ A -> Z
    $chairs = [];
    $columns = 10;

    $chairCount = 0; // Đếm số ghế đã tạo
    foreach ($rows as $row) {
        for ($column = 1; $column <= $columns; $column++) {
            if ($chairCount >= $chairNumber) {
                break 2; // Thoát khỏi cả hai vòng lặp
            }
            $chairs[] = [
                'id_room' => $idRoom,
                'chair_name' => $row . $column, // Ví dụ: A1, A2...
                'chair_status' => 'available',
                'column' => $column,
                'row' => $row,
                'price' => rand(50000, 200000), // Giá ngẫu nhiên
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $chairCount++;
        }
    }

    // Chèn toàn bộ ghế vào bảng chairs
    Chair::insert($chairs);
}

    public function update(Request $request, $id)
    {
        $room = Room::updateRoom($id, $request->all());
        if ($room) {
            return response()->json($room, 200);
        }
        return response()->json(['message' => 'Room not found'], 404);
    }

    public function destroy($id)
    {
        $deleted = Room::deleteRoom($id);
        if ($deleted) {
            return response()->json(['message' => 'Room deleted'], 200);
        }
        return response()->json(['message' => 'Room not found'], 404);
    }

    public function getActiveRooms()
    {
        $activeRooms = Room::getActiveRooms();
        return response()->json($activeRooms, 200);
    }
}
