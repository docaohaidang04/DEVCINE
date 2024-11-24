<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    protected $table = 'payment';
    protected $primaryKey = 'id_payment';
    protected $fillable = [
        'name',
    ];

    // Lấy tất cả thanh toán
    public static function getAllPayments()
    {
        return self::all();
    }

    // Lấy thanh toán theo ID
    public static function getPaymentById($id)
    {
        $payment = self::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }
        return $payment;
    }

    // Tạo thanh toán mới
    public static function createPayment($request)
    {
        // Xác thực và tạo thanh toán
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        return self::create($request->all());
    }

    // Cập nhật thanh toán
    public static function updatePayment($request, $id)
    {
        $payment = self::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Xác thực và cập nhật
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $payment->update($request->all());
        return $payment;
    }

    // Xóa thanh toán
    public static function deletePayment($id)
    {
        $payment = self::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $payment->delete();
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
