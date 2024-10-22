<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        return Promotion::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'Promotion_name' => 'required|string|max:255',
            'Description' => 'nullable|string',
            'Discount_value' => 'nullable|numeric',
            'Start_date' => 'nullable|date',
            'End_date' => 'nullable|date',
        ]);

        return Promotion::create($request->all());
    }

    public function show(Promotion $promotion)
    {
        return $promotion;
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'Promotion_name' => 'sometimes|required|string|max:255',
            'Description' => 'nullable|string',
            'Discount_value' => 'nullable|numeric',
            'Start_date' => 'nullable|date',
            'End_date' => 'nullable|date',
        ]);

        $promotion->update($request->all());
        return $promotion;
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return response()->json(null, 204);
    }
}
