<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Chair extends Model
{
    use HasFactory;

    protected $table = 'chairs'; // Tên bảng

    protected $primaryKey = 'id_chair'; // Khóa chính

    protected $fillable = [
        'id_room',
        'chair_name',
        'chair_status',
        'column',
        'row',
    ];

    // Lấy tất cả các ghế
    public static function getAllChairs(): Collection
    {
        return self::all();
    }

    // Tạo một ghế mới
    public static function createChair(array $data): Chair
    {
        return self::create($data);
    }

    // Lấy thông tin một ghế theo ID
    public static function getChairById(int $id): Chair
    {
        return self::findOrFail($id);
    }

    // Cập nhật thông tin một ghế
    public static function updateChair(int $id, array $data): Chair
    {
        $chair = self::findOrFail($id);
        $chair->update($data);
        return $chair;
    }

    // Xóa một ghế
    public static function deleteChair(int $id): void
    {
        $chair = self::findOrFail($id);
        $chair->delete();
    }

    // Mối quan hệ với bảng Room
    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room', 'id_room');
    }
}
