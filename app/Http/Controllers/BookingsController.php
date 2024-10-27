<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    public function index()
    {
        $bookings = Bookings::all();
        return response()->json($bookings);
    }

    public function show($id)
    {
        $booking = Bookings::find($id);
        if ($booking) {
            return response()->json($booking);
        }
        return response()->json(['message' => 'booking not found'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_account' => 'required|exists:accounts,id_account',
            'id_combo' => 'nullable|exists:combos,id_combo',
            'id_payment' => 'nullable|exists:payment,id_payment',
            'quantity' => 'nullable|integer',
            'total_amount' => 'nullable|numeric',
            'payment_status' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'payment_date' => 'nullable|date',
            'status' => 'nullable|string',
        ]);

        $booking = Bookings::create($request->all());
        return response()->json($booking, 201);
    }

    public function update(Request $request, $id)
    {
        $booking = Bookings::find($id);
        if (!$booking) {
            return response()->json(['message' => 'booking not found'], 404);
        }

        $request->validate([
            'id_account' => 'sometimes|required|exists:accounts,id_account',
            'id_combo' => 'nullable|exists:combos,id_combo',
            'id_payment' => 'nullable|exists:payment,id_payment',
            'quantity' => 'nullable|integer',
            'total_amount' => 'nullable|numeric',
            'payment_status' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'payment_date' => 'nullable|date',
            'status' => 'nullable|string',
        ]);

        $booking->update($request->all());
        return response()->json($booking);
    }

    public function destroy($id)
    {
        $booking = Bookings::find($id);
        if (!$booking) {
            return response()->json(['message' => 'booking not found'], 404);
        }

        $booking->delete();
        return response()->json(['message' => 'booking deleted successfully']);
    }
}
