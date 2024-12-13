<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChairShowtime extends Model
{
    // Nếu tên bảng không theo quy ước của Laravel (bảng số nhiều), bạn cần khai báo tên bảng:
    protected $table = 'chair_showtime';

    // Quan hệ với Chair
    public function chair()
    {
        return $this->belongsTo(Chair::class, 'id_chair');
    }

    // Quan hệ với Showtime
    public function showtime()
    {
        return $this->belongsTo(Showtime::class, 'id_showtime');
    }
}
