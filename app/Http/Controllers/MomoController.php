<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Bookings;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

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

        return response()->json($body);
    }

    public function handleMoMoReturn(Request $request)
    {
        Log::info('MoMo return response: ', $request->all());

        if ($request->resultCode == 0) {
            $bookingCode = $request->orderId;

            // Lấy booking từ database
            $booking = Bookings::where('booking_code', $bookingCode)->first();
            if ($booking) {
                // Cập nhật thông tin thanh toán thành công
                $booking->update([
                    'payment_status' => 'success',
                    'transaction_id' => $request->transId,
                    'payment_date' => now(),
                    'status' => 'true'
                ]);

                return response()->json(['message' => 'Thanh toán thành công']);
            }
        }

        return response()->json(['message' => 'Thanh toán thất bại']);
    }

    public function handleMoMoIPN(Request $request)
    {
        $status = $request->resultCode;
        $bookingCode = $request->orderId;

        // Lấy booking từ database
        $booking = Bookings::where('booking_code', $bookingCode)->first();

        if ($booking) {
            DB::beginTransaction();
            try {
                // Cập nhật thông tin thanh toán
                $booking->update([
                    'payment_status' => $status == 0 ? 'success' : 'cancel',
                    'transaction_id' => $request->transId,
                    'payment_date' => now(),
                    'status' => 'true'
                ]);

                // Nếu thanh toán thành công và có account_promotion_id, cập nhật status trong bảng account_promotion
                if ($status == 0 && $booking->account_promotion_id) {
                    DB::table('account_promotion')
                        ->where('account_promotion_id', $booking->account_promotion_id)
                        ->update(['status' => 'default']);
                }

                // Lấy vé liên quan đến booking
                $ticket = $booking->ticket;

                // Lấy showtime liên quan đến ticket
                $showtime = $ticket->showtime;

                // Lấy các ghế từ bảng `chair_showtime` liên quan đến `showtime` và `ticket`
                $chairs = DB::table('chair_showtime')
                    ->where('id_showtime', $showtime->id_showtime)
                    ->whereIn('id_chair', function ($query) use ($ticket) {
                        $query->select('chair_id')
                            ->from('ticket_chair')
                            ->where('ticket_id', $ticket->id_ticket);
                    })
                    ->get();

                // Cập nhật trạng thái ghế
                foreach ($chairs as $chair) {
                    DB::table('chair_showtime')
                        ->where('id_chair', $chair->id_chair)
                        ->where('id_showtime', $showtime->id_showtime)
                        ->update(['chair_status' => $status == 0 ? 'sold' : 'available']);
                }

                DB::commit();
                return response()->json(['message' => 'IPN handled successfully']);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['message' => 'Payment update failed'], 500);
            }
        } else {
            return response()->json(['message' => 'Booking not found'], 404);
        }
    }
}
