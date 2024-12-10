<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use Illuminate\Http\Request;

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

            return response()->json(['chairs' => $chairs], 200);
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
            $showtime = Showtime::createShowtime($request->all());
            if (isset($showtime['errors'])) {
                return response()->json($showtime, 422);
            }
            return response()->json($showtime, 201);
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
}
