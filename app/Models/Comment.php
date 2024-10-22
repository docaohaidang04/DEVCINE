<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments'; // Tên bảng trong cơ sở dữ liệu
    protected $primaryKey = 'id_comment'; // Đặt khóa chính là id_comment

    protected $fillable = [
        'ticket_id', // ID của ticket mà bình luận thuộc về
        'user_id', // ID của người dùng
        'content', // Nội dung bình luận
        'created_at', // Thời gian tạo
        'updated_at', // Thời gian cập nhật
    ];

    // Lấy tất cả bình luận
    public static function getAllComments()
    {
        return self::all(); // Trả về tất cả bình luận
    }

    // Tạo bình luận mới
    public static function createComment($data)
    {
        return self::create($data); // Tạo bình luận mới với dữ liệu
    }

    // Lấy một bình luận theo ID
    public static function getCommentById($id)
    {
        return self::find($id); // Tìm bình luận theo ID
    }

    // Cập nhật bình luận
    public function updateComment($data)
    {
        return $this->update($data); // Cập nhật bình luận với dữ liệu
    }

    // Xóa bình luận
    public function deleteComment()
    {
        return $this->delete(); // Xóa bình luận
    }
}
