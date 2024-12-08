<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function ticketSalesByDay(Request $request)
{
    // Lấy ngày hoặc khoảng thời gian từ query string
    $date = $request->query('date'); // Ngày cụ thể
    $startDate = $request->query('start_date'); // Khoảng thời gian bắt đầu
    $endDate = $request->query('end_date'); // Khoảng thời gian kết thúc
    $status = $request->query('status');  // Lấy giá trị trạng thái từ query string

    // Mặc định nếu không có tham số nào thì lấy ngày hiện tại
    $date = $date ?? Carbon::now()->format('Y-m-d'); // Mặc định là ngày hiện tại

    // Xây dựng truy vấn chỉ lấy dữ liệu từ bảng tickets
    $query = DB::table('tickets')
        ->groupBy(DB::raw('DATE(tickets.created_at)')) // Nhóm theo ngày tạo bản ghi trong bảng tickets
        ->select(
            DB::raw('DATE(tickets.created_at) as date'),
            DB::raw('COUNT(tickets.id_ticket) as total_tickets')
        );

    // Nếu có khoảng thời gian, áp dụng điều kiện
    if ($startDate && $endDate) {
        $query->whereBetween('tickets.created_at', [$startDate, $endDate]);
    } else {
        // Nếu có ngày, lọc theo ngày
        $query->whereDate('tickets.created_at', '=', $date);
    }

    // Nếu có trạng thái, lọc theo trạng thái vé
    if ($status) {
        $query->where('tickets.status', '=', $status);
    }

    // Thực hiện truy vấn
    $data = $query->get();

    // Nếu không có kết quả, trả về tổng số vé là 0
    if ($data->isEmpty()) {
        $data = collect([[ 
            'date' => Carbon::now()->format('Y-m-d'), // Trả về ngày hiện tại
            'total_tickets' => 0 // Mặc định là 0 vé
        ]]);
    }

    // Trả về kết quả
    return response()->json([
        'date' => $date,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'status' => $status,
        'total_tickets' => $data,
    ]);
}
    public function revenueByDate(Request $request)
    {
        // Lấy dữ liệu từ query string
        $date = $request->query('date'); // Ngày cụ thể (chỉ nhập ngày)
        $month = $request->query('month'); // Tháng cụ thể
        $year = $request->query('year', Carbon::now()->format('Y')); // Năm cụ thể, mặc định là năm hiện tại

        // Xây dựng truy vấn chỉ lấy dữ liệu từ bảng bookings
        $query = DB::table('bookings')
            ->select(
                DB::raw('SUM(bookings.total_amount) as total_revenue'),
                DB::raw('COUNT(bookings.id_booking) as total_bookings')
            )
            ->where('bookings.payment_status', 'success'); // Chỉ lấy những bản ghi có trạng thái thanh toán thành công

        // Xử lý các trường hợp lọc theo ngày, tháng, năm
        if ($date) {
            // Lọc theo ngày, mặc định tháng và năm hiện tại nếu không nhập
            $month = $month ?? Carbon::now()->format('m'); // Tháng hiện tại
            $year = $year ?? Carbon::now()->format('Y'); // Năm hiện tại

            $query->whereDay('bookings.booking_date', '=', $date)
                ->whereMonth('bookings.booking_date', '=', $month)
                ->whereYear('bookings.booking_date', '=', $year);
        } elseif ($month) {
            // Lọc theo tháng, mặc định năm hiện tại nếu không nhập
            $query->whereMonth('bookings.booking_date', '=', $month)
                ->whereYear('bookings.booking_date', '=', $year);
        } elseif ($year) {
            // Lọc theo năm
            $query->whereYear('bookings.booking_date', '=', $year);
        } else {
            // Nếu không nhập gì, mặc định lọc theo năm hiện tại
            $query->whereYear('bookings.booking_date', '=', Carbon::now()->format('Y'));
        }

        // Thực hiện truy vấn
        $data = $query->first();

        // Trả về kết quả
        return response()->json([
            'date' => $date,
            'month' => $month,
            'year' => $year,
            'total_revenue' => $data->total_revenue ?? 0,
            'total_bookings' => $data->total_bookings ?? 0,
        ]);
    }

    public function getRevenueByMovie(Request $request)
    {
        $movieId = $request->input('id_movie');
        $date = $request->input('date'); // Chỉ nhập ngày (vd: 28)
        $month = $request->input('month'); // Tháng cụ thể
        $year = $request->input('year'); // Năm cụ thể
        $startDate = $request->input('start_date'); // Ngày bắt đầu
        $endDate = $request->input('end_date'); // Ngày kết thúc

        // Xác minh dữ liệu đầu vào
        if (!$movieId) {
            return response()->json(['error' => 'id_movie is required'], 400);
        }

        $query = DB::table('bookings')
            ->join('tickets', 'bookings.id_ticket', '=', 'tickets.id_ticket')
            ->join('showtimes', 'tickets.id_showtime', '=', 'showtimes.id_showtime')
            ->join('movies', 'showtimes.id_movie', '=', 'movies.id_movie')
            ->where('movies.id_movie', $movieId);

        // Lọc theo ngày, tháng, năm, hoặc khoảng thời gian
        if ($date) {
            // Nếu không nhập tháng và năm, mặc định là tháng và năm hiện tại
            $currentMonth = $month ?? date('m');
            $currentYear = $year ?? date('Y');

            $query->whereDay('bookings.booking_date', $date)
                ->whereMonth('bookings.booking_date', $currentMonth)
                ->whereYear('bookings.booking_date', $currentYear);

        } elseif ($month) {
            $query->whereMonth('bookings.booking_date', $month)
            ->whereYear('bookings.booking_date', $year ?? date('Y')); // Nếu không nhập năm, mặc định là năm hiện tại

        } elseif ($year) {
            $query->whereYear('bookings.booking_date', $year);

        } elseif ($startDate && $endDate) {
            $query->whereBetween('bookings.booking_date', [$startDate, $endDate]);
        }

        // Thực hiện truy vấn
        $revenue = $query->select(
                'movies.movie_name as movie_name',
                DB::raw('SUM(bookings.total_amount) as total_revenue'),
                DB::raw('COUNT(bookings.id_booking) as total_bookings')
            )
            ->groupBy('movies.movie_name')
            ->first();

        // Nếu không tìm thấy dữ liệu
        if (!$revenue) {
            return response()->json(['message' => 'Không có doanh thu trong khoảng thời gian này'], 404);
        }
        return response()->json($revenue);
    }


}