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
        return response()->json(['message' => 'promotion not found'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_promotion' => 'required|exists:promotions,id_promotion',
            'id_booking' => 'required|exists:bookings,id_booking',
        ]);

        $promotion = BookingPromotion::create($request->all());
        return response()->json($promotion, 201);
    }

    public function update(Request $request, $id)
    {
        $promotion = BookingPromotion::find($id);
        if (!$promotion) {
            return response()->json(['message' => 'promotion not found'], 404);
        }

        $request->validate([
            'id_promotion' => 'sometimes|required|exists:promotions,id_promotion',
            'id_booking' => 'sometimes|required|exists:bookings,id_booking',
        ]);

        $promotion->update($request->all());
        return response()->json($promotion);
    }

    public function destroy($id)
    {
        $promotion = BookingPromotion::find($id);
        if (!$promotion) {
            return response()->json(['message' => 'promotion not found'], 404);
        }

        $promotion->delete();
        return response()->json(['message' => 'promotion deleted successfully']);
    }
}
