<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    use HasFactory;

    protected $table = 'combos';

    protected $primaryKey = 'id_combo';

    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];

    public static function getAllCombos()
    {
        return self::all();
    }

    public static function createCombo($data)
    {
        return self::create($data);
    }

    public static function getComboById($id)
    {
        return self::find($id);
    }

    public function updateCombo($data)
    {
        return $this->update($data);
    }

    public function deleteCombo()
    {
        return $this->delete();
    }
}
