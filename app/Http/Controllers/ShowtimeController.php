<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ShowtimeController extends Controller
{
    public function index()
    {
        $showtimes = Showtime::getAllShowtimes();
        return response()->json($showtimes, 200);
    }

    public function getChairsByShowtime($id_showtime)
    {
        try {
            $chairs = Showtime::getChairsWithStatusByShowtime($id_showtime);

            if ($chairs->isEmpty()) {
                return response()->json(['message' => 'No chairs found for this showtime.'], 404);
            }

            return response()->json($chairs, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $showtime = Showtime::with('movie', 'room', 'showtimeSlot')->find($id);
        if ($showtime) {
            return response()->json($showtime, 200);
        }
        return response()->json(['message' => 'Showtime not found'], 404);
    }

    public function store(Request $request)
    {
        try {
            return Showtime::createShowtime($request->all());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Showtime creation failed: ' . $e->getMessage()], 400);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $showtime = Showtime::updateShowtime($id, $request->all());
            if (isset($showtime['errors'])) {
                return response()->json($showtime, 422);
            }
            return response()->json($showtime, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Showtime update failed: ' . $e->getMessage()], 400);
        }
    }

    public function getNextShowtimes(Request $request, $id_movie)
    {
        $showtimes = Showtime::getNextShowtimesByMovieId($id_movie);
        return response()->json($showtimes, 200);
    }

    public function destroy($id)
    {
        $deleted = Showtime::deleteShowtime($id);
        if ($deleted) {
            return response()->json(['message' => 'Showtime deleted'], 200);
        }
        return response()->json(['message' => 'Showtime not found'], 404);
    }
    public function updateChairsByShowtime(Request $request, int $id_showtime)
    {
        try {
            // Xác thực dữ liệu
            // $validator = Validator::make($request->all(), [
            //     'chairs' => 'required|array',
            //     'chairs.*.id_chair' => 'required|exists:chairs,id_chair',
            //     'chairs.*.chair_status' => 'required|string|in:available,sold,booked',
            // ]);

            // if ($validator->fails()) {
            //     return response()->json(['errors' => $validator->errors()], 422);
            // }

            $chairs = $request->input('chairs');

            // Cập nhật trạng thái ghế bằng một câu lệnh bulk update
            foreach ($chairs as $chair) {
                DB::table('chair_showtime')
                    ->where('id_showtime', $id_showtime)
                    ->where('id', $chair['id_chair'])
                    ->update(['chair_status' => $chair['chair_status']]);
            }

            return response()->json(['message' => 'Chairs status updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update chairs status: ' . $e->getMessage()], 500);
        }
    }

    public function getAvailableSlots(Request $request)
    {
        $id_room = $request->input('id_room');
        $date_time = $request->input('date_time'); // Ngày cần kiểm tra (format: YYYY-MM-DD)

        try {
            // Truy vấn danh sách slot-time theo room và date_time
            $slots = DB::table('showtimes')
                ->join('showtime_slots', 'showtimes.id_slot', '=', 'showtime_slots.id_slot')
                ->select('showtime_slots.id_slot', 'showtime_slots.slot_time', 'showtimes.date_time')
                ->where('showtimes.id_room', $id_room)
                ->whereDate('showtimes.date_time', $date_time)
                ->get();


            if ($slots->isEmpty()) {
                return response()->json([], 200);
            }

            return response()->json($slots, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching slots: ' . $e->getMessage()], 500);
        }
    }
}
