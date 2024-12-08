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
        'slot_time',
    ];

    public function showtime()
    {
        return $this->hasOne(Showtime::class, 'id_slot');
    }

    public static function validateSlotData($data)
    {
        $validator = Validator::make($data, [
            'slot_time' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return true;
    }

    public static function getAllSlots()
    {
        return self::all();
    }

    public static function createSlot($data)
    {
        $validationResult = self::validateSlotData($data);
        if ($validationResult !== true) {
            return $validationResult;
        }

        return self::create($data);
    }

    public static function getSlotById($id)
    {
        return self::find($id);
    }

    public function updateSlot($data)
    {
        $validationResult = self::validateSlotData($data);
        if ($validationResult !== true) {
            return $validationResult;
        }

        return $this->update($data);
    }

    public function deleteSlot()
    {
        return $this->delete();
    }
}
