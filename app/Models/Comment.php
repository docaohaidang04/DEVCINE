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
        'id_movies',     // Thay 'ticket_id' thành 'id_movies'
        'id_account',    // Thay 'user_id' thành 'id_account'
        'content',
        'rating',
    ];

    // Lấy tất cả bình luận
    public static function getAllComments()
    {
        return self::all();
    }

    public static function getCommentsByMovieId($id_movie)
    {
        return self::where('id_movies', $id_movie)->get();
    }

    // Tạo bình luận mới
    public static function createComment($data)
    {
        // Xác thực dữ liệu
        $validator = Validator::make($data, [
            'id_movies' => 'required|integer',
            'id_account' => 'required|integer',
            'content' => 'required|string|max:1000',
            'rating' => 'required|integer|between:1,5',  // Thêm điều kiện cho rating nếu cần
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

    public static function getRatingSummaryByMovieId($id_movie)
    {
        return self::where('id_movies', $id_movie)
            ->selectRaw('AVG(rating) as average_rating, COUNT(content) as total_comments')
            ->first();
    }
}
