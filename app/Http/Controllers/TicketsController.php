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
        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'id_booking' => 'required|integer',
            'id_showtime' => 'required|integer',
            'id_chair' => 'required|integer',
            'price' => 'required|numeric',
            'status' => 'required|string|max:255',
        ]);

        // Tạo ticket mới
        $ticket = Tickets::createTicket($validatedData); // Gọi phương thức trong model
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

        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'id_booking' => 'sometimes|required|integer',
            'id_showtime' => 'sometimes|required|integer',
            'id_chair' => 'sometimes|required|integer',
            'price' => 'sometimes|required|numeric',
            'status' => 'sometimes|required|string|max:255',
        ]);

        // Cập nhật ticket
        $ticket->updateTicket($validatedData); // Gọi phương thức trong model
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
