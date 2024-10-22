<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPromotion extends Model
{
    use HasFactory;

    protected $table = 'booking_promotions';
    protected $primaryKey = 'ID_PROMOTION_AP';
    protected $fillable = [
        'ID_PROMOTION',
        'ID_BOOKING',
    ];
}
