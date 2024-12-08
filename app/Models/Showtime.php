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
        'id_slot',
        'date_time',
        'start_time',
        'end_time',
    ];

    public function showtimeSlot()
    {
        return $this->belongsTo(ShowtimeSlot::class, 'id_slot');
    }


    public static function validateShowtimeData($data, $multiple = false)
    {
        $rules = [
            'id_movie' => 'required|integer|exists:movies,id_movie',
            'id_room' => 'required|integer|exists:rooms,id_room',
            'date_time' => 'required|date',
            'start_time' => 'required|date|after_or_equal:date_time',
            'end_time' => 'nullable|date|after:start_time',
        ];

        if ($multiple) {
            $rules['id_slots'] = 'required|array';
            $rules['id_slots.*'] = 'required|integer|exists:showtime_slots,id_slot';
        } else {
            $rules['id_slot'] = 'required|integer|exists:showtime_slots,id_slot';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return true;
    }

    /* public static function getNextShowtimesByMovieId($id_movie)
    {
        return self::where('id_movie', $id_movie)
            ->whereDate('date_time', '>=', Carbon::today())
            ->orderBy('date_time')
            ->take(5)
            ->get();
    } */
    public static function getNextShowtimesByMovieId($id_movie)
    {
        return self::where('id_movie', $id_movie)
            ->whereDate('date_time', '>=', Carbon::today())
            ->orderBy('date_time')
            ->get()
            ->unique(function ($showtime) {
                return $showtime->date_time->format('d/m');
            })
            ->take(5);
    }

    public static function getAllShowtimes()
    {
        return self::with('movie', 'room', 'showtimeSlot')->get();
    }

    public static function getShowtimeById($id)
    {
        return self::with('movie', 'room', 'showtimeSlot')->find($id);
    }

    public static function createShowtime($data)
    {
        if (isset($data['id_slots'])) {
            $validationResult = self::validateShowtimeData($data, true);
            if ($validationResult !== true) {
                return $validationResult;
            }

            $showtimes = [];
            foreach ($data['id_slots'] as $id_slot) {
                $showtimeData = $data;
                $showtimeData['id_slot'] = $id_slot;
                unset($showtimeData['id_slots']);
                $showtimes[] = self::create($showtimeData);
            }
            return $showtimes;
        } else {
            $validationResult = self::validateShowtimeData($data);
            if ($validationResult !== true) {
                return $validationResult;
            }
            return self::create($data);
        }
    }

    public static function updateShowtime($id, $data)
    {
        $showtime = self::find($id);
        if (!$showtime) {
            return response()->json(['message' => 'Showtime not found'], 404);
        }

        $validationResult = self::validateShowtimeData($data);
        if ($validationResult !== true) {
            return $validationResult;
        }

        return $showtime->update($data);
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
