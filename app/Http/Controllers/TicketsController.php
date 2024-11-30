<?php

namespace App\Http\Controllers;

use App\Models\Tickets;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    // Lấy danh sách tất cả các vé
    public function index()
    {
        $tickets = Tickets::getAllTickets(); // Gọi phương thức trong model
        return response()->json($tickets); // Trả về danh sách dưới dạng JSON
    }

    // Tạo mới một vé
    public function store(Request $request)
    {
        // Gọi phương thức tạo vé trong model
        $ticket = Tickets::createTicket($request->all());

        // Kiểm tra nếu có lỗi xác thực
        if (isset($ticket['errors'])) {
            return response()->json($ticket, 422); // Trả về lỗi với mã 422
        }

        return response()->json($ticket, 201); // Trả về vé mới tạo
    }

    // Đặt ghế cho một khung giờ cụ thể
    public function bookChair($showtime_id, $chair_id)
    {
        // Gọi phương thức bookChair trong model
        $ticket = Tickets::bookChair($showtime_id, $chair_id);

        // Kiểm tra nếu có lỗi xác thực
        if (isset($ticket['error'])) {
            return response()->json($ticket, 400); // Trả về lỗi với mã 400
        }

        return response()->json($ticket, 201); // Trả về vé mới tạo
    }

    // Lấy thông tin của một vé theo ID
    public function show($id)
    {
        $ticket = Tickets::getTicketById($id); // Gọi phương thức trong model
        if (!$ticket) {
            return response()->json(['message' => 'Không tìm thấy vé'], 404);
        }
        return response()->json($ticket); // Trả về thông tin vé
    }

    // Cập nhật một vé theo ID
    public function update(Request $request, $id)
    {
        $ticket = Tickets::getTicketById($id); // Gọi phương thức trong model
        if (!$ticket) {
            return response()->json(['message' => 'Không tìm thấy vé'], 404);
        }

        // Gọi phương thức cập nhật vé trong model
        $updatedTicket = $ticket->updateTicket($request->all());

        // Kiểm tra nếu có lỗi xác thực
        if (isset($updatedTicket['errors'])) {
            return response()->json($updatedTicket, 422); // Trả về lỗi với mã 422
        }

        return response()->json($updatedTicket);
    }

    // Xóa một vé theo ID
    public function destroy($id)
    {
        $ticket = Tickets::getTicketById($id); // Gọi phương thức trong model
        if (!$ticket) {
            return response()->json(['message' => 'Không tìm thấy vé'], 404);
        }

        // Xóa vé
        $ticket->deleteTicket(); // Gọi phương thức trong model
        return response()->json(['message' => 'Vé đã được xóa']);
    }
}
