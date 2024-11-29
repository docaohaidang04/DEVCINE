<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Exception;

class CountryController extends Controller
{
    public function getCountries()
    {
        try {
            $response = Http::get('https://countriesnow.space/api/v0.1/countries');

            if ($response->successful()) {
                $countries = $response->json();
                return response()->json([
                    'status' => 'success',
                    'data' => $countries,
                ], 200);
            }

            // Nếu không thành công nhưng không gặp lỗi hệ thống
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to fetch countries.',
            ], $response->status());
        } catch (Exception $e) {
            // Xử lý lỗi kết nối hoặc lỗi khác
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching countries.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
