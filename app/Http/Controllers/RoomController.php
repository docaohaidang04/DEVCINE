<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Chair;

class RoomController extends Controller
{
    // Lấy tất cả các phòng
    public function index()
    {
        $rooms = Room::getAllRooms();
        return response()->json($rooms, 200);
    }

    // Lấy thông tin phòng theo ID
    public function show($id)
    {
        $room = Room::getRoomById($id);
        if ($room) {
            return response()->json($room, 200);
        }
        return response()->json(['message' => 'Room not found'], 404);
    }

    // Tạo phòng mới
    public function store(Request $request)
    {
        try {
            // Tạo phòng và tự động sinh ghế nếu có chair_number
            $room = Room::createRoom($request->only(['room_name', 'room_status', 'room_type', 'chair_number']));
            if (isset($room['errors'])) {
                return response()->json($room, 422);
            }

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

    // Cập nhật phòng
    public function update(Request $request, $id)
    {
        $room = Room::updateRoom($id, $request->all());
        if ($room) {
            return response()->json($room, 200);
        }
        return response()->json(['message' => 'Room not found'], 404);
    }

    // Xóa phòng
    public function destroy($id)
    {
        $deleted = Room::deleteRoom($id);
        if ($deleted) {
            return response()->json(['message' => 'Room deleted'], 200);
        }
        return response()->json(['message' => 'Room not found'], 404);
    }

    // Lấy danh sách phòng đang hoạt động
    public function getActiveRooms()
    {
        $activeRooms = Room::getActiveRooms();
        return response()->json($activeRooms, 200);
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
                    break 2;
                }
                $chairs[] = [
                    'id_room' => $idRoom,
                    'chair_name' => $row . $column,
                    'chair_status' => 'available',
                    'column' => $column,
                    'row' => $row,
                    'price' => 60000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $chairCount++;
            }
        }

        // Chèn toàn bộ ghế vào bảng chairs
        Chair::insert($chairs);
    }
}
