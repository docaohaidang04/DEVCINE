<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chair;

class SeatController extends Controller
{
    public function getSeats()
    {
        $seats = Chair::with(['chairShowtime' => function ($query) {
            $query->select('id_chair', 'chair_status');
        }])->get();

        return response()->json($seats);
    }
}
