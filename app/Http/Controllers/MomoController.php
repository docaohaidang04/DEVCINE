<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Bookings;
use GuzzleHttp\Client;

class MomoController extends Controller
{
    public function createMomoPayment(Request $request)
    {
        $accountId = $request->input('account_id');
        $bookingCode = $request->input('booking_code');
        $amount = $request->input('total_amount');
        $orderId = $bookingCode;

        $orderInfo = "Thanh toán qua MoMo";
        $redirectUrl = env('MOMO_REDIRECT_URL');
        $ipnUrl = env('MOMO_IPN_URL');
        $extraData = "";

        $partnerCode = env('MOMO_PARTNER_CODE');
        $accessKey = env('MOMO_ACCESS_KEY');
        $secretKey = env('MOMO_SECRET_KEY');
        $requestId = time();
        $requestType = "payWithATM";
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'accessKey' => $accessKey,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        $client = new Client();
        $response = $client->post(env('MOMO_API_URL'), [
            'json' => $data
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        if (isset($body['payUrl'])) {
            $booking = Bookings::create([
                'account_id' => $accountId,
                'booking_code' => $bookingCode,
                'total_amount' => $amount,
                'status' => 'pending',
            ]);

            return response()->json($body);
        }

        return response()->json($body);
    }


    public function handleMoMoReturn(Request $request)
    {
        Log::info('MoMo return response: ', $request->all());

        if ($request->resultCode == 0) {
            $bookingCode = $request->orderId;

            $booking = Bookings::where('booking_code', $bookingCode)->first();
            if ($booking) {
                $booking->update([
                    'payment_status' => 'success',
                    'transaction_id' => $request->transId,
                    'payment_date' => now(),
                    'status' => 'true'
                ]);
            }

            return response()->json(['message' => 'Thanh toán thành công']);
        }

        return response()->json(['message' => 'Thanh toán thất bại']);
    }

    public function handleMoMoIPN(Request $request)
    {
        Log::info('MoMo IPN response: ', $request->all());

        if ($request->resultCode == 0) {
            $bookingCode = $request->orderId;

            $booking = Bookings::where('booking_code', $bookingCode)->first();
            if ($booking) {
                $booking->update([
                    'payment_status' => 'success',
                    'transaction_id' => $request->transId,
                    'payment_date' => now(),
                    'status' => 'true'
                ]);
            }
        }

        return response()->json(['message' => 'IPN handled successfully']);
    }
}
