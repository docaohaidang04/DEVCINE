<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    // Lấy tất cả bookings
    public function index()
    {
        $bookings = Bookings::getAllBookings();
        return response()->json($bookings);
    }

    // Lấy booking theo id
    public function show($id)
    {
        $booking = Bookings::getBookingById($id);
        if ($booking) {
            return response()->json($booking);
        }
        return response()->json(['message' => 'booking not found'], 404);
    }

    // Tạo booking mới
    public function store(Request $request)
    {
        $booking = Bookings::createBooking($request->all());
        if ($booking instanceof \Illuminate\Http\JsonResponse) {
            return $booking; // Trả về lỗi nếu có
        }

        return response()->json($booking, 201);
    }

    // Cập nhật booking
    public function update(Request $request, $id)
    {
        $booking = Bookings::getBookingById($id);
        if (!$booking) {
            return response()->json(['message' => 'booking not found'], 404);
        }

        $updatedBooking = $booking->updateBooking($request->all());
        if ($updatedBooking instanceof \Illuminate\Http\JsonResponse) {
            return $updatedBooking; // Trả về lỗi nếu có
        }

        return response()->json($updatedBooking);
    }

    // Xóa booking
    public function destroy($id)
    {
        return Bookings::deleteBooking($id);
    }
}
