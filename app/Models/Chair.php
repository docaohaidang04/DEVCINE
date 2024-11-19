<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Chair extends Model
{
    use HasFactory;

    protected $table = 'chairs';

    protected $primaryKey = 'id_chair';

    protected $fillable = [
        'id_room',
        'chair_name',
        'chair_status',
        'column',
        'row',
        'price'
    ];

    public static function getAllChairs(): Collection
    {
        return self::all();
    }

    public static function createChair(array $data): Chair
    {
        return self::create($data);
    }

    public static function getChairById(int $id): Chair
    {
        return self::findOrFail($id);
    }

    public static function updateChair(int $id, array $data): Chair
    {
        $chair = self::findOrFail($id);
        $chair->update($data);
        return $chair;
    }

    public static function deleteChair(int $id): void
    {
        $chair = self::findOrFail($id);
        $chair->delete();
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room', 'id_room');
    }
}
