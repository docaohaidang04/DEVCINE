<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room;

class Showtime extends Model
{
    use HasFactory;

    protected $table = 'showtimes';

    protected $primaryKey = 'id_showtime';

    protected $fillable = [
        'ID_MOVIE',
        'ID_ROOM',
        'Start_time',
        'End_time',
    ];

    // Lấy tất cả các suất chiếu
    public static function getAllShowtimes()
    {
        return self::with('movie', 'room')->get();
    }

    // Lấy suất chiếu theo ID
    public static function getShowtimeById($id)
    {
        return self::with('movie', 'room')->find($id);
    }

    // Tạo suất chiếu mới
    public static function createShowtime($data)
    {
        return self::create($data);
    }

    // Cập nhật suất chiếu theo ID
    public static function updateShowtime($id, $data)
    {
        $showtime = self::find($id);
        if ($showtime) {
            $showtime->update($data);
            return $showtime;
        }
        return null;
    }

    // Xóa suất chiếu theo ID
    public static function deleteShowtime($id)
    {
        $showtime = self::find($id);
        if ($showtime) {
            $showtime->delete();
            return true;
        }
        return false;
    }

    // Quan hệ với Movie
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'ID_MOVIE');
    }

    // Quan hệ với Room
    public function room()
    {
        return $this->belongsTo(Room::class, 'ID_ROOM');
    }
}
