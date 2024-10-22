<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    use HasFactory;

    protected $table = 'tickets'; // Tên bảng trong cơ sở dữ liệu
    protected $primaryKey = 'id_ticket'; // Đặt khóa chính là id_ticket

    protected $fillable = [
        'ID_BOOKING',
        'ID_SHOWTIME',
        'ID_CHAIR',
        'PRICE',
        'STATUS',
    ];

    // Lấy tất cả tickets
    public static function getAllTickets()
    {
        return self::all(); // Trả về tất cả tickets
    }

    // Tạo ticket mới
    public static function createTicket($data)
    {
        return self::create($data); // Tạo ticket mới với dữ liệu
    }

    // Lấy một ticket theo ID
    public static function getTicketById($id)
    {
        return self::find($id); // Tìm ticket theo ID
    }

    // Cập nhật ticket
    public function updateTicket($data)
    {
        return $this->update($data); // Cập nhật ticket với dữ liệu
    }

    // Xóa ticket
    public function deleteTicket()
    {
        return $this->delete(); // Xóa ticket
    }
}
