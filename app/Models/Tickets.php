<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Tickets extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $primaryKey = 'id_ticket';

    protected $fillable = [
        'id_showtime',
        'status',
    ];

    public function chairs()
    {
        return $this->belongsToMany(Chair::class, 'ticket_chair', 'ticket_id', 'chair_id');
    }

    // Lấy tất cả các tickets
    public static function getAllTickets()
    {
        return self::all();
    }

    // Tạo ticket mới
    public static function createTicket($data)
    {
        // Xác thực dữ liệu
        $validator = Validator::make($data, [
            'id_showtime' => 'required|exists:showtimes,id_showtime',
            'id_chairs' => 'required|array',
            'id_chairs.*' => 'required|exists:chairs,id_chair',
            'status' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            // Trả về một mảng lỗi thay vì JsonResponse
            return ['errors' => $validator->errors()];
        }

        // Lưu ticket
        $ticket = self::create([
            'id_showtime' => $data['id_showtime'],
            'status' => $data['status'] ?? null,
        ]);

        // Gắn mối quan hệ chairs với ticket
        $ticket->chairs()->sync($data['id_chairs']); // Lưu nhiều chairs cho một ticket

        return $ticket;
    }


    // Lấy ticket theo ID
    public static function getTicketById($id)
    {
        return self::find($id);
    }

    // Cập nhật ticket
    public function updateTicket($data)
    {
        // Xác thực dữ liệu
        $validator = Validator::make($data, [
            'id_showtime' => 'sometimes|required|exists:showtimes,id_showtime',
            'id_chair' => 'sometimes|required|exists:chairs,id_chair',
            'status' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        $this->update($data);
        return $this;
    }


    // Xóa ticket
    public function deleteTicket()
    {
        $this->delete();
        return ['message' => 'Ticket deleted successfully'];
    }
}
