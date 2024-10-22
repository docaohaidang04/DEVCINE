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
        return response()->json(['message' => 'Booking not found'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ID_ACCOUNT' => 'required|exists:accounts,ID_ACCOUNT',
            'ID_COMBO' => 'nullable|exists:combos,ID_COMBO',
            'ID_PAYMENT' => 'nullable|exists:payment,ID_PAYMENT',
            'Quantity' => 'nullable|integer',
            'Total_amount' => 'nullable|numeric',
            'Payment_status' => 'nullable|string',
            'Transaction_id' => 'nullable|string',
            'Payment_date' => 'nullable|date',
            'Status' => 'nullable|string',
        ]);

        $booking = Bookings::create($request->all());
        return response()->json($booking, 201);
    }

    public function update(Request $request, $id)
    {
        $booking = Bookings::find($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $request->validate([
            'ID_ACCOUNT' => 'sometimes|required|exists:accounts,ID_ACCOUNT',
            'ID_COMBO' => 'nullable|exists:combos,ID_COMBO',
            'ID_PAYMENT' => 'nullable|exists:payment,ID_PAYMENT',
            'Quantity' => 'nullable|integer',
            'Total_amount' => 'nullable|numeric',
            'Payment_status' => 'nullable|string',
            'Transaction_id' => 'nullable|string',
            'Payment_date' => 'nullable|date',
            'Status' => 'nullable|string',
        ]);

        $booking->update($request->all());
        return response()->json($booking);
    }

    public function destroy($id)
    {
        $booking = Bookings::find($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $booking->delete();
        return response()->json(['message' => 'Booking deleted successfully']);
    }
}
