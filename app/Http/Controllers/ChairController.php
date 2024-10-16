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

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'ID_ROOM' => 'required|exists:rooms,ID_ROOM',
            'Chair_name' => 'nullable|string',
            'Chair_status' => 'nullable|string',
            'Column' => 'nullable|integer',
            'Row' => 'nullable|integer',
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
            'ID_ROOM' => 'required|exists:rooms,ID_ROOM',
            'Chair_name' => 'nullable|string',
            'Chair_status' => 'nullable|string',
            'Column' => 'nullable|integer',
            'Row' => 'nullable|integer',
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
