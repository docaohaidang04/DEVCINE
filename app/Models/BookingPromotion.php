<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPromotion extends Model
{
    use HasFactory;

    protected $table = 'booking_promotions';

    protected $primaryKey = 'id_promotion_ap';

    protected $fillable = [
        'id_promotion',
        'id_booking',
    ];
}
