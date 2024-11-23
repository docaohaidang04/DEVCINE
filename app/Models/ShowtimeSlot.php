<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

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

    // Xác thực dữ liệu khung giờ chiếu
    public static function validateSlotData($data)
    {
        $validator = Validator::make($data, [
            'slot_time' => 'required|date_format:H:i'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return true;
    }

    // Lấy tất cả các khung giờ chiếu
    public static function getAllSlots()
    {
        return self::all();
    }

    // Tạo khung giờ chiếu mới
    public static function createSlot($data)
    {
        // Xác thực dữ liệu
        $validationResult = self::validateSlotData($data);
        if ($validationResult !== true) {
            return $validationResult;
        }

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
        // Xác thực dữ liệu
        $validationResult = self::validateSlotData($data);
        if ($validationResult !== true) {
            return $validationResult;
        }

        return $this->update($data);
    }

    // Xóa khung giờ chiếu
    public function deleteSlot()
    {
        return $this->delete();
    }
}
