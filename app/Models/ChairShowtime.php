<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChairShowtime extends Model
{
    public function chair()
    {
        return $this->belongsTo(Chair::class, 'chair_id');
    }
}
