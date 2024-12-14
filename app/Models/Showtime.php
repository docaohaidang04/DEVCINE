<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Room;
use App\Models\Movie;
use App\Models\Chair;
use App\Models\ShowtimeSlot;
use Carbon\Carbon;

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

    protected $casts = [
        'date_time' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function showtimeSlot()
    {
        return $this->belongsTo(ShowtimeSlot::class, 'id_slot');
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'id_movie');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room');
    }

    public function chairs()
    {
        return $this->belongsToMany(Chair::class, 'chair_showtime', 'id_showtime', 'id_chair')
            ->withPivot('chair_status');
    }

    public function tickets()
    {
        return $this->hasMany(Tickets::class, 'id_showtime', 'id_showtime');
    }

    public static function getChairsWithStatusByShowtime($id_showtime)
    {
        return DB::table('chair_showtime')
            ->where('id_showtime', $id_showtime)
            ->join('chairs', 'chair_showtime.id_chair', '=', 'chairs.id_chair')
            ->select(
                'chair_showtime.id',
                'chair_showtime.id_chair',
                'chairs.chair_name',
                'chairs.row',
                'chairs.column',
                'chairs.price',
                'chair_showtime.chair_status',
            )
            ->orderBy('chairs.row')
            ->orderBy('chairs.column')
            ->get();
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
        $createdShowtimes = [];

        foreach ($data['id_slots'] as $id_slot) {
            $showtimeData = $data;
            $showtimeData['id_slot'] = $id_slot;

            // Tạo suất chiếu
            $showtime = self::create($showtimeData);
            $createdShowtimes[] = $showtime->load('movie', 'room', 'showtimeSlot')->toArray();

            // Lấy danh sách ghế trong phòng
            $chairs = Chair::where('id_room', $data['id_room'])->get();

            // Khởi tạo trạng thái ghế cho suất chiếu
            foreach ($chairs as $chair) {
                DB::table('chair_showtime')->insert([
                    'id_chair' => $chair->id_chair,
                    'id_showtime' => $showtime->id_showtime,
                    'id_slot' => $id_slot,
                    'chair_status' => 'available',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json([
            'message' => 'Showtimes created successfully',
            'data' => $createdShowtimes,
        ], 201);
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

    public static function getChairsByShowtime($id_showtime)
    {
        return DB::table('chair_showtime')
            ->where('id_showtime', $id_showtime)
            ->join('chairs', 'chair_showtime.id_chair', '=', 'chairs.id_chair')
            ->select('chairs.*', 'chair_showtime.chair_status')
            ->get();
    }
}
