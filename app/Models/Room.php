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
        'room_name',
        'room_status',
        'room_type',
        'chair_number',
    ];

    public static function getAllRooms()
    {
        return self::all();
    }

    public static function getRoomById($id)
    {
        return self::find($id);
    }

    public static function createRoom($data)
    {
        return self::create($data);
    }

    public static function updateRoom($id, $data)
    {
        $room = self::find($id);
        if ($room) {
            $room->update($data);
            return $room;
        }
        return null;
    }

    public static function deleteRoom($id)
    {
        $room = self::find($id);
        if ($room) {
            return $room->delete();
        }
        return false;
    }

    public static function getActiveRooms()
    {
        return self::where('room_status', 'active')->get();
    }
}
