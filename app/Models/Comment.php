<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';
    protected $primaryKey = 'id_comment';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'content',
    ];

    // Lấy tất cả bình luận
    public static function getAllComments()
    {
        return self::all();
    }

    // Tạo bình luận mới
    public static function createComment($data)
    {
        // Xác thực dữ liệu
        $validator = Validator::make($data, [
            'ticket_id' => 'required|integer',
            'user_id' => 'required|integer',
            'content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return self::create($data);
    }

    // Lấy bình luận theo ID
    public static function getCommentById($id)
    {
        return self::find($id);
    }

    // Cập nhật bình luận
    public function updateComment($data)
    {
        return $this->update($data);
    }

    // Xóa bình luận
    public function deleteComment()
    {
        return $this->delete();
    }
}
