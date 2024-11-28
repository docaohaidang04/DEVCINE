<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VNPayController extends Controller
{
    // public function createPayment(Request $request)
    // {
    //     $vnp_TmnCode = env('VNP_TMN_CODE'); // Mã website tại VNPAY
    //     $vnp_HashSecret = env('VNP_HASH_SECRET'); // Chuỗi bí mật
    //     $vnp_Url = env('VNP_URL'); // URL thanh toán của VNPAY
    //     $vnp_ReturnUrl = env('VNP_RETURN_URL'); // URL trả về sau khi thanh toán

    //     $vnp_TxnRef = $request->booking_code; // Mã giao dịch (unique)
    //     $vnp_OrderInfo = "Thanh toán đơn hàng";
    //     $vnp_OrderType = "billpayment";
    //     $vnp_Amount = $request->total_amount * 100; // Tổng số tiền (VND)
    //     $vnp_Locale = "vn"; // Ngôn ngữ
    //     /* $vnp_BankCode = $request->bank_code ?? ''; */ // Mã ngân hàng
    //     $vnp_BankCode = 'NCB';
    //     $vnp_IpAddr = $request->ip(); // Địa chỉ IP khách hàng

    //     $inputData = [
    //         "vnp_Version" => "2.1.0",
    //         "vnp_TmnCode" => $vnp_TmnCode,
    //         "vnp_Amount" => $vnp_Amount,
    //         "vnp_Command" => "pay",
    //         "vnp_CreateDate" => date('YmdHis'),
    //         "vnp_CurrCode" => "VND",
    //         "vnp_IpAddr" => $vnp_IpAddr,
    //         "vnp_Locale" => $vnp_Locale,
    //         "vnp_OrderInfo" => $vnp_OrderInfo,
    //         "vnp_OrderType" => $vnp_OrderType,
    //         "vnp_ReturnUrl" => $vnp_ReturnUrl,
    //         "vnp_TxnRef" => $vnp_TxnRef,
    //     ];

    //     if (!empty($vnp_BankCode)) {
    //         $inputData['vnp_BankCode'] = $vnp_BankCode;
    //     }

    //     ksort($inputData);
    //     $query = http_build_query($inputData);
    //     $vnp_Url = $vnp_Url . "?" . $query;

    //     $hashdata = urldecode($query);
    //     $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
    //     $vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;

    //     return response()->json(['url' => $vnp_Url]);
    // }

    public function createPayment(Request $request)
    {
        $vnp_CreateDate = date('YmdHis');
        $vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes', strtotime($vnp_CreateDate)));

        $vnp_TmnCode = env('VNP_TMN_CODE'); // Mã website tại VNPAY
        $vnp_HashSecret = env('VNP_HASH_SECRET'); // Chuỗi bí mật
        $vnp_Url = env('VNP_URL'); // URL thanh toán của VNPAY
        $vnp_ReturnUrl = env('VNP_RETURN_URL'); // URL trả về sau khi thanh toán

        // Tham số đầu vào
        // input('booking_code', uniqid()
        $vnp_TxnRef = $request->booking_code; // Mã giao dịch (unique)
        $vnp_OrderInfo = $request->input('order_desc', 'Thanh toán đơn hàng');
        $vnp_OrderType = $request->input('order_type', 'billpayment');
        $vnp_Amount = $request->input('total_amount', 0) * 100; // Tổng số tiền (VND)
        $vnp_Locale = $request->input('language', 'vn'); // Ngôn ngữ
        $vnp_BankCode = $request->input('bank_code', null); // Mã ngân hàng
        $vnp_ExpireDate = $request->input('expire_date', now()->addMinutes(15)->format('YmdHis'));
        $vnp_IpAddr = $request->ip(); // Địa chỉ IP khách hàng

        // Dữ liệu đầu vào
        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_ExpireDate" => $vnp_ExpireDate, // Thêm thời gian hết hạn
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];


        if ($vnp_BankCode) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        // Sắp xếp dữ liệu và tạo URL
        ksort($inputData);
        $query = http_build_query($inputData);
        $hashdata = urldecode($query);
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= "?" . $query . '&vnp_SecureHash=' . $vnpSecureHash;

        // Phản hồi
        return response()->json([
            'code' => '00',
            'message' => 'Success',
            'data' => $vnp_Url,
        ]);
    }


    public function paymentCallback(Request $request)
    {
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $inputData = $request->all();
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        ksort($inputData);
        $hashData = urldecode(http_build_query($inputData));
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash == $vnp_SecureHash) {
            if ($inputData['vnp_ResponseCode'] == '00') {
                return response()->json(['status' => 'success', 'message' => 'Giao dịch thành công']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Giao dịch thất bại']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Chữ ký không hợp lệ']);
        }
    }
}
