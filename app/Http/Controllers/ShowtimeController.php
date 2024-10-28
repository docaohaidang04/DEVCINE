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
        // Xác thực dữ liệu
        $request->validate([
            'id_movie' => 'required|integer|exists:movies,id_movie', // Kiểm tra id_movie có tồn tại
            'id_room' => 'required|integer|exists:rooms,id_room', // Kiểm tra id_room có tồn tại
            'date_time' => 'required|date', // Đảm bảo trường date_time có giá trị
            'start_time' => 'required|date|after_or_equal:date_time', // start_time phải lớn hơn hoặc bằng date_time
            'end_time' => 'nullable|date|after:start_time', // end_time phải lớn hơn start_time
        ]);

        // Tạo suất chiếu mới
        $showtime = Showtime::createShowtime($request->all());

        return response()->json($showtime, 201);
    }

    // Cập nhật suất chiếu
    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu
        $request->validate([
            'id_movie' => 'sometimes|required|integer|exists:movies,id_movie',
            'id_room' => 'sometimes|required|integer|exists:rooms,id_room',
            'date_time' => 'sometimes|required|date',
            'start_time' => 'sometimes|required|date|after_or_equal:date_time',
            'end_time' => 'sometimes|date|after:start_time',
        ]);

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
