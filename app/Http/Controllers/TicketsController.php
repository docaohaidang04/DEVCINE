<?php

namespace App\Http\Controllers;

use App\Models\Tickets;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    // Lấy danh sách tất cả các tickets
    public function index()
    {
        $tickets = Tickets::getAllTickets(); // Gọi phương thức trong model
        return response()->json($tickets); // Trả về danh sách dưới dạng JSON
    }

    // Tạo mới một ticket
    public function store(Request $request)
    {
        // Gọi phương thức tạo ticket trong model
        $ticket = Tickets::createTicket($request->all());

        // Nếu có lỗi xác thực, trả về lỗi
        if (isset($ticket['errors'])) {
            return response()->json($ticket, 422);
        }

        return response()->json($ticket, 201); // Trả về ticket mới tạo
    }

    // Lấy thông tin của một ticket theo ID
    public function show($id)
    {
        $ticket = Tickets::getTicketById($id); // Gọi phương thức trong model
        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }
        return response()->json($ticket); // Trả về thông tin ticket
    }

    // Cập nhật một ticket theo ID
    public function update(Request $request, $id)
    {
        $ticket = Tickets::getTicketById($id); // Gọi phương thức trong model
        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        // Gọi phương thức cập nhật ticket trong model
        $ticket->updateTicket($request->all());

        // Nếu có lỗi xác thực, trả về lỗi
        if (isset($ticket['errors'])) {
            return response()->json($ticket, 422);
        }

        return response()->json($ticket);
    }

    // Xóa một ticket theo ID
    public function destroy($id)
    {
        $ticket = Tickets::getTicketById($id); // Gọi phương thức trong model
        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        // Xóa ticket
        $ticket->deleteTicket(); // Gọi phương thức trong model
        return response()->json(['message' => 'Ticket deleted']);
    }
}
