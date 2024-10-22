<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function index()
    {
        $payments = Payments::all();
        return response()->json($payments);
    }

    public function show($id)
    {
        $payment = Payments::find($id);
        if ($payment) {
            return response()->json($payment);
        }
        return response()->json(['message' => 'Payment not found'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Name' => 'required|string|max:255',
        ]);

        $payment = Payments::create($request->all());
        return response()->json($payment, 201);
    }

    public function update(Request $request, $id)
    {
        $payment = Payments::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $request->validate([
            'Name' => 'sometimes|required|string|max:255',
        ]);

        $payment->update($request->all());
        return response()->json($payment);
    }

    public function destroy($id)
    {
        $payment = Payments::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $payment->delete();
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
