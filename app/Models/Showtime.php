<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room;
use Carbon\Carbon;

class Showtime extends Model
{
    use HasFactory;

    protected $table = 'showtimes';

    protected $primaryKey = 'id_showtime';

    protected $fillable = [
        'id_movie',
        'id_room',
        'date_time', // Thêm trường date_time vào đây
        'start_time',
        'end_time',
    ];

    public function showtimeSlots()
    {
        return $this->belongsToMany(ShowtimeSlot::class, 'showtime_slot_showtime', 'id_showtime', 'id_slot');
    }

    public static function getNextShowtimesByMovieId($id_movie)
    {
        return self::where('id_movie', $id_movie)
            ->where('date_time', '>=', Carbon::now()) // Chỉ lấy các suất chiếu từ thời điểm hiện tại trở đi
            ->orderBy('date_time')
            ->take(5) // Lấy 5 suất chiếu tiếp theo
            ->get();
    }

    public static function getAllShowtimes()
    {
        return self::with('movie', 'room')->get();
    }

    public static function getShowtimeById($id)
    {
        return self::with('movie', 'room')->find($id);
    }

    public static function createShowtime($data)
    {
        return self::create($data);
    }

    public static function updateShowtime($id, $data)
    {
        $showtime = self::find($id);
        if ($showtime) {
            $showtime->update($data);
            return $showtime;
        }
        return null;
    }

    public static function deleteShowtime($id)
    {
        $showtime = self::find($id);
        if ($showtime) {
            $showtime->delete();
            return true;
        }
        return false;
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'id_movie');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room');
    }
}
