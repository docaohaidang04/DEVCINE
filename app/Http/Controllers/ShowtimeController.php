<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use Illuminate\Http\Request;

class ShowtimeController extends Controller
{
    // Lấy tất cả các suất chiếu
    public function index()
    {
        $showtimes = Showtime::getAllShowtimes();
        return response()->json($showtimes, 200);
    }

    // Lấy suất chiếu theo ID
    public function show($id)
    {
        $showtime = Showtime::with('movie', 'room', 'showtimeSlots')->find($id);
        if ($showtime) {
            return response()->json($showtime, 200);
        }
        return response()->json(['message' => 'Showtime not found'], 404);
    }

    // Tạo suất chiếu mới
    public function store(Request $request)
    {
        try {
            // Tạo suất chiếu mới và lưu các khung giờ chiếu
            $showtime = Showtime::createShowtime($request->only(['id_movie', 'id_room', 'date_time', 'start_time', 'end_time', 'slots']));
            if (isset($showtime['errors'])) {
                return response()->json($showtime, 422);
            }

            return response()->json($showtime->load('showtimeSlots'), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Showtime creation failed: ' . $e->getMessage()], 400);
        }
    }

    // Cập nhật suất chiếu
    public function update(Request $request, $id)
    {
        try {
            $showtime = Showtime::updateShowtime($id, $request->all());
            if (isset($showtime['errors'])) {
                return response()->json($showtime, 422);
            }

            return response()->json($showtime->load('showtimeSlots'), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Showtime update failed: ' . $e->getMessage()], 400);
        }
    }

    // Lấy danh sách suất chiếu tiếp theo của bộ phim
    public function getNextShowtimes(Request $request, $id_movie)
    {
        $showtimes = Showtime::getNextShowtimesByMovieId($id_movie);
        return response()->json($showtimes, 200);
    }

    // Xóa suất chiếu
    public function destroy($id)
    {
        $deleted = Showtime::deleteShowtime($id);
        if ($deleted) {
            return response()->json(['message' => 'Showtime deleted'], 200);
        }
        return response()->json(['message' => 'Showtime not found'], 404);
    }
}
