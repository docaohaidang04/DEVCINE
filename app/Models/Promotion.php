<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Promotion extends Model
{
    use HasFactory;

    protected $table = 'promotions';

    protected $primaryKey = 'ID_PROMOTION';

    protected $fillable = [
        'Promotion_name',
        'Description',
        'Discount_type',
        'Discount_value',
        'Start_date',
        'End_date',
        'Min_purchase_amount',
        'Max_discount_amount'
    ];

    public $timestamps = true;

    // Method to get all promotions
    public static function getAllPromotions(): Collection
    {
        return self::all();
    }

    // Method to create a new promotion
    public static function createPromotion(array $data): Promotion
    {
        return self::create($data);
    }

    // Method to find a promotion by ID
    public static function findPromotion($id): Promotion
    {
        return self::findOrFail($id);
    }

    // Method to update a promotion
    public function updatePromotion(array $data): bool
    {
        return $this->update($data);
    }

    // Method to delete a promotion
    public function deletePromotion(): bool
    {
        return $this->delete();
    }
}

