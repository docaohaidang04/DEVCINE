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

    // Quan hệ nhiều vé với nhiều ghế thông qua ticket_chair
    public function chairs()
    {
        return $this->belongsToMany(Chair::class, 'ticket_chair', 'ticket_id', 'chair_id');
    }

    // Quan hệ một vé thuộc về một suất chiếu
    public function showtime()
    {
        return $this->belongsTo(Showtime::class, 'id_showtime', 'id_showtime');
    }
    // Lấy tất cả các tickets
    public static function getAllTickets()
    {
        return self::all();
    }
    public function bookings()
    {
        return $this->hasMany(Bookings::class, 'id_ticket', 'id_ticket');
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

    // Đặt ghế
    public static function bookChair($showtime_id, $chair_id)
    {
        // Kiểm tra xem ghế đã được đặt cho khung giờ này chưa
        $existingTicket = self::where('id_showtime', $showtime_id)
            ->whereHas('chairs', function ($query) use ($chair_id) {
                $query->where('chairs.id_chair', $chair_id); // Sửa đổi ở đây
            })
            ->first();

        if ($existingTicket) {
            return response()->json(['error' => 'Ghế đã được đặt cho khung giờ này.'], 400);
        }

        // Tạo vé mới
        $ticket = self::create([
            'id_showtime' => $showtime_id,
            'status' => 'booked'
        ]);

        // Gắn mối quan hệ chair với ticket
        $ticket->chairs()->attach($chair_id);

        return $ticket;
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
        return ['message' => 'Vé đã được xóa thành công'];
    }
}
