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
        $showtime = Showtime::getShowtimeById($id);
        if ($showtime) {
            return response()->json($showtime, 200);
        }
        return response()->json(['message' => 'Showtime not found'], 404);
    }

    // Tạo suất chiếu mới
    public function store(Request $request)
    {
        $showtime = Showtime::createShowtime($request->all());
        return response()->json($showtime, 201);
    }

    // Cập nhật suất chiếu
    public function update(Request $request, $id)
    {
        $showtime = Showtime::updateShowtime($id, $request->all());
        if ($showtime) {
            return response()->json($showtime, 200);
        }
        return response()->json(['message' => 'Showtime not found'], 404);
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
