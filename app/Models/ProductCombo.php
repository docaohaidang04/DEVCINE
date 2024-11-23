<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCombo extends Model
{
    use HasFactory;

    protected $table = 'product_combos';

    protected $fillable = [
        'id_combo',
        'id_product',
        'quantity',
    ];

    // Định nghĩa quan hệ với Combo
    public function combo()
    {
        return $this->belongsTo(Combo::class, 'id_combo');
    }

    // Định nghĩa quan hệ với Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
