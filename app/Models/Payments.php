<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    protected $table = 'payment';
    protected $primaryKey = 'id_payment'; // Đổi ID_PAYMENT thành id_payment
    protected $fillable = [
        'name', // Đổi Name thành name
    ];
}
