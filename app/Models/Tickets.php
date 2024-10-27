<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public static function getAllTickets()
    {
        return self::all();
    }

    public static function createTicket($data)
    {
        return self::create($data);
    }

    public static function getTicketById($id)
    {
        return self::find($id);
    }

    public function updateTicket($data)
    {
        return $this->update($data);
    }

    public function deleteTicket()
    {
        return $this->delete();
    }
}
