<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCombo extends Model
{
    use HasFactory;

    protected $table = 'product_combos';
    protected $primaryKey = 'id_product_combo';

    protected $fillable = [
        'id_combo',
        'id_product',
        'quantity'
    ];

    public $timestamps = true;

    public static function getAllCombos()
    {
        return self::all();
    }

    public static function getComboById($id)
    {
        return self::find($id);
    }

    public static function createCombo($data)
    {
        return self::create($data);
    }

    public static function updateCombo($id, $data)
    {
        $combo = self::find($id);
        if ($combo) {
            $combo->update($data);
            return $combo;
        }
        return null;
    }

    public static function deleteCombo($id)
    {
        $combo = self::find($id);
        if ($combo) {
            $combo->delete();
            return true;
        }
        return false;
    }
}
