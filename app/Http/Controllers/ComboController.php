<?php

namespace App\Http\Controllers;

use App\Models\Combo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ComboController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Combo::getAllCombos());
    }

    public function store(Request $request): JsonResponse
    {
        $combo = Combo::createCombo($request);
        return response()->json($combo, 201);
    }

    public function show($id): JsonResponse
    {
        $combo = Combo::getComboById($id);
        if (!$combo) {
            return response()->json(['message' => 'Combo not found'], 404);
        }
        return response()->json($combo);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $combo = Combo::updateCombo($id, $request);
        if (!$combo) {
            return response()->json(['message' => 'Combo not found'], 404);
        }
        return response()->json($combo);
    }

    public function destroy($id): JsonResponse
    {
        $combo = Combo::getComboById($id);
        if (!$combo) {
            return response()->json(['message' => 'Combo not found'], 404);
        }
        $combo->deleteCombo();
        return response()->json(['message' => 'Combo deleted']);
    }
}
