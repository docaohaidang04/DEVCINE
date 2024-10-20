<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $primaryKey = 'id_room';

    protected $fillable = [
        'Room_name',
        'Room_status',
        'Room_type',
        'Chair_number',
    ];

    // Lấy tất cả các phòng
    public static function getAllRooms()
    {
        return self::all();
    }

    // Tìm phòng theo ID
    public static function getRoomById($id)
    {
        return self::find($id);
    }

    // Tạo phòng mới
    public static function createRoom($data)
    {
        return self::create($data);
    }

    // Cập nhật phòng theo ID
    public static function updateRoom($id, $data)
    {
        $room = self::find($id);
        if ($room) {
            $room->update($data);
            return $room;
        }
        return null;
    }

    // Xóa phòng theo ID
    public static function deleteRoom($id)
    {
        $room = self::find($id);
        if ($room) {
            return $room->delete();
        }
        return false;
    }

    // Lấy tất cả các phòng đang hoạt động
    public static function getActiveRooms()
    {
        return self::where('Room_status', 'active')->get();
    }
}
