<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChairShowtime;
use Illuminate\Support\Facades\DB;
use App\Models\Tickets;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;

class Chair extends Model
{
    use HasFactory;

    protected $table = 'chairs';

    protected $primaryKey = 'id_chair';

    protected $fillable = [
        'id_room',
        'chair_name',
        'chair_status',
        'column',
        'row',
        'price',
    ];
    public function ticket()
    {
        return $this->belongsTo(Tickets::class, 'ticket_id');
    }
    public function chairShowtime()
    {
        return $this->hasOne(ChairShowtime::class, 'chair_id');
    }

    // Lấy tất cả chairs
    public static function getAllChairs(): Collection
    {
        return self::all();
    }

    // Lấy chair theo id
    public static function getChairById(int $id): Chair
    {
        return self::findOrFail($id);
    }

    // Tạo mới một chair
    public static function createChair(array $data): Chair
    {
        $validator = Validator::make($data, [
            'id_room' => 'required|exists:rooms,id_room',
            'chair_name' => 'nullable|string',
            'chair_status' => 'nullable|string',
            'column' => 'nullable|integer',
            'row' => 'nullable|string',
            'price' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return self::create($data);
    }

    // Cập nhật chair
    public static function updateChair(int $id, array $data): Chair
    {
        $validator = Validator::make($data, [
            'id_room' => 'required|exists:rooms,id_room',
            'chair_name' => 'nullable|string',
            'chair_status' => 'nullable|string',
            'column' => 'nullable|integer',
            'row' => 'nullable|string',
            'price' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $chair = self::findOrFail($id);
        $chair->update($data);
        return $chair;
    }

    // Xóa chair
    public static function deleteChair(int $id): void
    {
        $chair = self::findOrFail($id);
        $chair->delete();
    }

    // Mối quan hệ với Room
    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room', 'id_room');
    }

    public function showtimes()
    {
        return $this->belongsToMany(Showtime::class, 'chair_showtime', 'id_chair', 'id_showtime')
            ->withPivot('chair_status');
    }

    public static function bookChair($id_showtime, $id_chair, $id_slot)
    {
        // Kiểm tra xem ghế đã được đặt cho suất chiếu và khung giờ chưa
        $existingBooking = DB::table('chair_showtime')
            ->where('id_showtime', $id_showtime)
            ->where('id_slot', $id_slot)
            ->where('id_chair', $id_chair)
            ->first();

        if ($existingBooking) {
            return response()->json(['message' => 'Ghế đã được đặt !'], 400);
        }

        // Lưu ghế vào bảng chair_showtime
        DB::table('chair_showtime')->insert([
            'id_showtime' => $id_showtime,
            'id_chair' => $id_chair,
            'id_slot' => $id_slot,
            'chair_status' => 'booked',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Đặt ghế thành công'], 200);
    }
}
