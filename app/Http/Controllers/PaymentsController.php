<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function index()
    {
        // Lấy tất cả thanh toán
        return response()->json(Payments::getAllPayments());
    }

    public function show($id)
    {
        // Lấy thanh toán theo ID
        $payment = Payments::getPaymentById($id);
        return response()->json($payment);
    }

    public function store(Request $request)
    {
        // Tạo thanh toán mới
        $payment = Payments::createPayment($request);
        return response()->json($payment, 201);
    }

    public function update(Request $request, $id)
    {
        // Cập nhật thanh toán
        $payment = Payments::updatePayment($request, $id);
        return response()->json($payment);
    }

    public function destroy($id)
    {
        // Xóa thanh toán
        return Payments::deletePayment($id);
    }
}
