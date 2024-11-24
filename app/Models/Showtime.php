<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class Showtime extends Model
{
    use HasFactory;

    protected $table = 'showtimes';

    protected $primaryKey = 'id_showtime';

    protected $fillable = [
        'id_movie',
        'id_room',
        'date_time',
        'start_time',
        'end_time',
    ];

    public function showtimeSlots()
    {
        return $this->belongsToMany(ShowtimeSlot::class, 'showtime_slot_showtime', 'id_showtime', 'id_slot');
    }

    // Xác thực dữ liệu suất chiếu
    public static function validateShowtimeData($data)
    {
        $validator = Validator::make($data, [
            'id_movie' => 'required|integer|exists:movies,id_movie',
            'id_room' => 'required|integer|exists:rooms,id_room',
            'date_time' => 'required|date',
            'start_time' => 'required|date|after_or_equal:date_time',
            'end_time' => 'nullable|date|after:start_time',
            'slots' => 'required|array', // Dữ liệu khung giờ chiếu
            'slots.*' => 'required|integer|exists:showtime_slots,id_slot', // ID khung giờ chiếu phải tồn tại
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return true;
    }

    // Lấy các suất chiếu gần nhất cho bộ phim
    public static function getNextShowtimesByMovieId($id_movie)
    {
        return self::where('id_movie', $id_movie)
            ->whereDate('date_time', '>=', Carbon::today()) // So sánh chỉ theo ngày
            ->orderBy('date_time')
            ->take(5)
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
        // Xác thực dữ liệu
        $validationResult = self::validateShowtimeData($data);
        if ($validationResult !== true) {
            return $validationResult;
        }

        $showtime = self::create($data);

        // Lưu các khung giờ chiếu vào bảng trung gian
        foreach ($data['slots'] as $slotId) {
            $showtime->showtimeSlots()->attach($slotId);
        }

        return $showtime;
    }

    public static function updateShowtime($id, $data)
    {
        $showtime = self::find($id);
        if (!$showtime) {
            return response()->json(['message' => 'Showtime not found'], 404);
        }

        // Xác thực dữ liệu
        $validationResult = self::validateShowtimeData($data);
        if ($validationResult !== true) {
            return $validationResult;
        }

        $showtime->update($data);

        // Cập nhật các khung giờ chiếu
        if (isset($data['slots'])) {
            // Xóa các khung giờ cũ
            $showtime->showtimeSlots()->detach();

            // Thêm các khung giờ chiếu mới
            foreach ($data['slots'] as $slotId) {
                $showtime->showtimeSlots()->attach($slotId);
            }
        }

        return $showtime;
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
