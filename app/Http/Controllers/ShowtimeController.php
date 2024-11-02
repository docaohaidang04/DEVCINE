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



    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $request->validate([
            'id_movie' => 'required|integer|exists:movies,id_movie',
            'id_room' => 'required|integer|exists:rooms,id_room',
            'date_time' => 'required|date',
            'start_time' => 'required|date|after_or_equal:date_time',
            'end_time' => 'nullable|date|after:start_time',
            'slots' => 'required|array', // Dữ liệu khung giờ chiếu
            'slots.*' => 'required|integer|exists:showtime_slots,id_slot' // ID khung giờ chiếu phải tồn tại
        ]);

        // Tạo suất chiếu mới
        $showtime = Showtime::create($request->only(['id_movie', 'id_room', 'date_time', 'start_time', 'end_time']));

        // Lưu các khung giờ chiếu vào bảng trung gian
        foreach ($request->slots as $slotId) {
            $showtime->showtimeSlots()->attach($slotId);
        }

        return response()->json($showtime->load('showtimeSlots'), 201);
    }


    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu
        $request->validate([
            'id_movie' => 'sometimes|required|integer|exists:movies,id_movie',
            'id_room' => 'sometimes|required|integer|exists:rooms,id_room',
            'date_time' => 'sometimes|required|date',
            'start_time' => 'sometimes|required|date|after_or_equal:date_time',
            'end_time' => 'sometimes|date|after:start_time',
            'slots' => 'sometimes|array', // Nếu có cập nhật khung giờ
            'slots.*' => 'required|integer|exists:showtime_slots,id_slot' // ID khung giờ chiếu phải tồn tại
        ]);

        $showtime = Showtime::find($id);
        if (!$showtime) {
            return response()->json(['message' => 'Showtime not found'], 404);
        }

        // Cập nhật thông tin suất chiếu
        $showtime->update($request->only(['id_movie', 'id_room', 'date_time', 'start_time', 'end_time']));

        // Cập nhật các khung giờ chiếu
        if ($request->has('slots')) {
            // Xóa các khung giờ cũ
            $showtime->showtimeSlots()->detach();

            // Thêm các khung giờ chiếu mới
            foreach ($request->slots as $slotId) {
                $showtime->showtimeSlots()->attach($slotId);
            }
        }

        return response()->json($showtime->load('showtimeSlots'), 200);
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
