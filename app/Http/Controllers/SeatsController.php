<?php

namespace App\Http\Controllers;

use App\Models\Chair;

class SeatController extends Controller
{
    public function getSeats()
    {
        $seats = Chair::with(['chairShowtime' => function ($query) {
            $query->select('id_chair', 'chair_status'); // Chọn chỉ những trường cần thiết
        }])->get();

        return response()->json($seats); // Trả về dữ liệu cho frontend dưới dạng JSON
    }
}
