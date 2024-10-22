<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $fillable = [
        'Promotion_name',
        'Description',
        'Discount_value',
        'Start_date',
        'End_date',
    ];
}
