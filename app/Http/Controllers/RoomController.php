<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

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
        $room = Room::createRoom($request->all());
        return response()->json($room, 201);
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
