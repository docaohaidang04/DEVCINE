<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $primaryKey = 'id_booking';

    protected $fillable = [
        'id_account',
        'id_combo',
        'id_payment',
        'booking_date',
        'quantity',
        'total_amount',
        'payment_status',
        'transaction_id',
        'payment_date',
        'status',
    ];
}
