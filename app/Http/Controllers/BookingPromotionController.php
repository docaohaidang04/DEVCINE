<?php

namespace App\Http\Controllers;

use App\Models\BookingPromotion;
use Illuminate\Http\Request;

class BookingPromotionController extends Controller
{
    public function index()
    {
        $promotions = BookingPromotion::all();
        return response()->json($promotions);
    }

    public function show($id)
    {
        $promotion = BookingPromotion::find($id);
        if ($promotion) {
            return response()->json($promotion);
        }
        return response()->json(['message' => 'Promotion not found'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ID_PROMOTION' => 'required|exists:promotions,ID_PROMOTION',
            'ID_BOOKING' => 'required|exists:bookings,ID_BOOKING',
        ]);

        $promotion = BookingPromotion::create($request->all());
        return response()->json($promotion, 201);
    }

    public function update(Request $request, $id)
    {
        $promotion = BookingPromotion::find($id);
        if (!$promotion) {
            return response()->json(['message' => 'Promotion not found'], 404);
        }

        $request->validate([
            'ID_PROMOTION' => 'sometimes|required|exists:promotions,ID_PROMOTION',
            'ID_BOOKING' => 'sometimes|required|exists:bookings,ID_BOOKING',
        ]);

        $promotion->update($request->all());
        return response()->json($promotion);
    }

    public function destroy($id)
    {
        $promotion = BookingPromotion::find($id);
        if (!$promotion) {
            return response()->json(['message' => 'Promotion not found'], 404);
        }

        $promotion->delete();
        return response()->json(['message' => 'Promotion deleted successfully']);
    }
}
