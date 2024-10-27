<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Promotion extends Model
{
    use HasFactory;

    protected $table = 'promotions';

    protected $primaryKey = 'id_promotion';

    protected $fillable = [
        'promotion_name',
        'description',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'min_purchase_amount',
        'max_discount_amount'
    ];

    public $timestamps = true;

    public static function getAllPromotions(): Collection
    {
        return self::all();
    }

    public static function createPromotion(array $data): Promotion
    {
        return self::create($data);
    }

    public static function findPromotion($id): Promotion
    {
        return self::findOrFail($id);
    }

    public function updatePromotion(array $data): bool
    {
        return $this->update($data);
    }

    public function deletePromotion(): bool
    {
        return $this->delete();
    }
}
