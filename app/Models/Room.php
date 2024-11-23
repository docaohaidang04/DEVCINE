<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $primaryKey = 'id_room';

    protected $fillable = [
        'room_name',
        'room_status',
        'room_type',
        'chair_number',
    ];

    // Lấy tất cả các phòng
    public static function getAllRooms()
    {
        return self::all();
    }

    // Lấy phòng theo ID
    public static function getRoomById($id)
    {
        return self::find($id);
    }

    // Tạo phòng mới với validation
    public static function createRoom($data)
    {
        // Validate dữ liệu
        $validationResult = self::validateRoomData($data);
        if ($validationResult !== true) {
            return $validationResult;
        }

        return self::create($data);
    }

    // Cập nhật phòng với validation
    public static function updateRoom($id, $data)
    {
        $room = self::find($id);
        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        // Validate dữ liệu
        $validationResult = self::validateRoomData($data);
        if ($validationResult !== true) {
            return $validationResult;
        }

        $room->update($data);
        return $room;
    }

    // Xóa phòng
    public static function deleteRoom($id)
    {
        $room = self::find($id);
        if ($room) {
            return $room->delete();
        }
        return false;
    }

    // Lấy các phòng đang hoạt động
    public static function getActiveRooms()
    {
        return self::where('room_status', 'active')->get();
    }

    // Hàm xác thực dữ liệu phòng
    private static function validateRoomData($data)
    {
        $validator = Validator::make($data, [
            'room_name' => 'required|string|max:255',
            'room_status' => 'nullable|string',
            'room_type' => 'nullable|string',
            'chair_number' => 'nullable|integer|min:1', // Số ghế phải là số nguyên dương
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return true;
    }
}
