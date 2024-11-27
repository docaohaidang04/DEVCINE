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
        'id_chair',
        'status',
    ];

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
            'id_chair' => 'required|exists:chairs,id_chair',
            'status' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        return self::create($data);
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
