<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::getAllRooms();
        return response()->json($rooms, 200);
    }

    public function show($id)
    {
        $room = Room::getRoomById($id);
        if ($room) {
            return response()->json($room, 200);
        }
        return response()->json(['message' => 'Room not found'], 404);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'room_name' => 'required|string|max:255',
                'room_status' => 'nullable|string',
                'room_type' => 'nullable|string',
                'chair_number' => 'nullable|integer',
            ]);

            $room = Room::create($request->all());

            return response()->json($room, 201);
        } catch (\Exception $e) {
            Log::error('Room creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Room creation failed: ' . $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $room = Room::updateRoom($id, $request->all());
        if ($room) {
            return response()->json($room, 200);
        }
        return response()->json(['message' => 'Room not found'], 404);
    }

    public function destroy($id)
    {
        $deleted = Room::deleteRoom($id);
        if ($deleted) {
            return response()->json(['message' => 'Room deleted'], 200);
        }
        return response()->json(['message' => 'Room not found'], 404);
    }

    public function getActiveRooms()
    {
        $activeRooms = Room::getActiveRooms();
        return response()->json($activeRooms, 200);
    }
}
