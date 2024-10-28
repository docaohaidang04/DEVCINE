<?php

namespace App\Http\Controllers;

use App\Models\Chair;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChairController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Chair::getAllChairs());
    }

    public function getChairsByRoom($id_room): JsonResponse
    {
        $chairs = Chair::where('id_room', $id_room)->get();
        return response()->json($chairs);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'id_room' => 'required|exists:rooms,id_room',
            'chair_name' => 'nullable|string',
            'chair_status' => 'nullable|string',
            'column' => 'nullable|integer',
            'row' => 'nullable|integer',
        ]);

        $chair = Chair::createChair($request->all());

        return response()->json($chair, 201);
    }

    public function show($id): JsonResponse
    {
        $chair = Chair::getChairById($id);
        return response()->json($chair);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'id_room' => 'required|exists:rooms,id_room',
            'chair_name' => 'nullable|string',
            'chair_status' => 'nullable|string',
            'column' => 'nullable|integer',
            'row' => 'nullable|integer',
        ]);

        $chair = Chair::updateChair($id, $request->all());

        return response()->json($chair);
    }

    public function destroy($id): JsonResponse
    {
        Chair::deleteChair($id);
        return response()->json(null, 204);
    }
}
