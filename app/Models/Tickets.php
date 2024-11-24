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
        'id_booking',
        'id_showtime',
        'id_chair',
        'price',
        'status',
    ];

    // Xác thực dữ liệu của ticket
    public static function validateTicketData($data, $isUpdate = false)
    {
        $rules = [
            'id_booking' => 'required|integer',
            'id_showtime' => 'required|integer',
            'id_chair' => 'required|integer',
            'price' => 'required|numeric',
            'status' => 'required|string|max:255',
        ];

        // Nếu là cập nhật, các trường có thể bỏ qua
        if ($isUpdate) {
            $rules = array_map(function ($rule) {
                return 'sometimes|' . $rule;
            }, $rules);
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return true;
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
        $validationResult = self::validateTicketData($data);
        if ($validationResult !== true) {
            return $validationResult;
        }

        return self::create($data);
    }

    // Lấy thông tin ticket theo ID
    public static function getTicketById($id)
    {
        return self::find($id);
    }

    // Cập nhật ticket
    public function updateTicket($data)
    {
        // Xác thực dữ liệu
        $validationResult = self::validateTicketData($data, true);
        if ($validationResult !== true) {
            return $validationResult;
        }

        return $this->update($data);
    }

    // Xóa ticket
    public function deleteTicket()
    {
        return $this->delete();
    }
}
