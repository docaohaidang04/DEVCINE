<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $primaryKey = 'ID_BOOKING';
    protected $fillable = [
        'ID_ACCOUNT',
        'ID_COMBO',
        'ID_PAYMENT',
        'Booking_date',
        'Quantity',
        'Total_amount',
        'Payment_status',
        'Transaction_id',
        'Payment_date',
        'Status',
    ];
}
