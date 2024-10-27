<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';
    protected $primaryKey = 'id_comment';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'content',
        'created_at',
        'updated_at',
    ];


    public static function getAllComments()
    {
        return self::all();
    }

    public static function createComment($data)
    {
        return self::create($data);
    }

    public static function getCommentById($id)
    {
        return self::find($id);
    }

    public function updateComment($data)
    {
        return $this->update($data);
    }

    public function deleteComment()
    {
        return $this->delete();
    }
}
