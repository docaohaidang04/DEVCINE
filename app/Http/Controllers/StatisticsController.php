<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    // Thống kê vé bán theo ngày
    public function ticketSalesByDay(Request $request)
    {
        // Lấy ngày từ query string, nếu không có thì lấy ngày hôm nay
        $date = $request->query('date', Carbon::now()->format('Y-m-d'));

        // Thực hiện truy vấn lấy tổng số vé bán theo ngày
        $data = DB::table('tickets')
            ->join('bookings', 'tickets.id_booking', '=', 'bookings.id_booking')
            ->whereDate('bookings.booking_date', $date)
            ->select(DB::raw('COUNT(tickets.id_ticket) as total_tickets'))
            ->first();

        // Trả kết quả về API
        return response()->json([
            'date' => $date,
            'total_tickets' => $data->total_tickets ?? 0,
        ]);
    }

    // Thống kê doanh thu theo ngày, tháng, năm
    public function revenueStatistics(Request $request)
    {
            // Nhận loại thống kê từ query string, mặc định là 'day'
        $type = $request->query('type', 'day'); // 'day', 'month', 'year'

        // Nhận ngày từ query string, mặc định là ngày hôm nay
        $date = $request->query('date', Carbon::now()->format('Y-m-d'));

        // Xây dựng truy vấn cho thống kê doanh thu
        $query = DB::table('bookings')
            ->whereNotNull('payment_date'); // Chỉ lấy các booking đã có thanh toán

        // Kiểm tra loại thống kê (theo ngày, tháng, năm)
        switch ($type) {
            case 'month':
                $query->whereYear('payment_date', Carbon::parse($date)->year)
                    ->whereMonth('payment_date', Carbon::parse($date)->month);
                break;

            case 'year':
                $query->whereYear('payment_date', Carbon::parse($date)->year);
                break;

            default: // day (mặc định là ngày)
                $query->whereDate('payment_date', $date);
                break;
        }

        // Tính tổng doanh thu
        $data = $query->select(DB::raw('SUM(total_amount) as total_revenue'))->first();

        // Trả về kết quả
        return response()->json([
            // 'type' => $type,
            'date' => $date,
            'total_revenue' => $data->total_revenue ?? 0,
        ]);
    }

    // Thống kê doanh thu theo phim
public function revenueByMovie(Request $request)
{
    $query = DB::table('bookings')
        ->join('tickets', 'bookings.id_booking', '=', 'tickets.id_booking')
        ->join('showtimes', 'tickets.id_showtime', '=', 'showtimes.id_showtime')
        ->join('movies', 'showtimes.id_movie', '=', 'movies.id_movie')
        ->groupBy('movies.id_movie', 'movies.movie_name')
        ->select(
            'movies.movie_name',
            DB::raw('SUM(bookings.total_amount) as total_revenue'),
            DB::raw('COUNT(tickets.id_ticket) as total_tickets')
        );

    // Kiểm tra nếu có tên phim được truyền vào
    if ($request->has('movie_name')) {
        $query->where('movies.movie_name', 'LIKE', '%' . $request->input('movie_name') . '%');
    }

    $data = $query->get();

    return response()->json($data);
}

}
