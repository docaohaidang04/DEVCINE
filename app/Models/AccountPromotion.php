<?php

namespace App\Models;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Model;

class AccountPromotion extends Model
{
    protected $table = 'account_promotion';
    protected $primaryKey = 'account_promotion_id';
    protected $fillable = ['account_id', 'promotion_id', 'status'];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id', 'id_promotion');
    }
}
