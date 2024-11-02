<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowtimeSlot extends Model
{
    use HasFactory;

    protected $table = 'showtime_slots';
    protected $primaryKey = 'id_slot';
    public $timestamps = true;

    protected $fillable = [
        'slot_time'
    ];

    public function showtimes()
    {
        return $this->belongsToMany(Showtime::class, 'showtime_slot_showtime', 'id_slot', 'id_showtime');
    }

    // Lấy tất cả các khung giờ chiếu
    public static function getAllSlots()
    {
        return self::all();
    }

    // Tạo khung giờ chiếu mới
    public static function createSlot($data)
    {
        return self::create($data);
    }

    // Lấy thông tin khung giờ chiếu theo ID
    public static function getSlotById($id)
    {
        return self::find($id);
    }

    // Cập nhật khung giờ chiếu
    public function updateSlot($data)
    {
        return $this->update($data);
    }

    // Xóa khung giờ chiếu
    public function deleteSlot()
    {
        return $this->delete();
    }
}
