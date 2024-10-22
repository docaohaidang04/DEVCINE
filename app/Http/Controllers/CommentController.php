<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Lấy danh sách tất cả các bình luận
    public function index()
    {
        $comments = Comment::getAllComments(); // Gọi phương thức trong model
        return response()->json($comments); // Trả về danh sách bình luận dưới dạng JSON
    }

    // Tạo mới một bình luận
    public function store(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'ticket_id' => 'required|integer',
            'user_id' => 'required|integer',
            'content' => 'required|string|max:1000',
        ]);

        // Tạo bình luận mới
        $comment = Comment::createComment($validatedData); // Gọi phương thức trong model
        return response()->json($comment, 201); // Trả về bình luận mới tạo
    }

    // Lấy thông tin của một bình luận theo ID
    public function show($id)
    {
        $comment = Comment::getCommentById($id); // Gọi phương thức trong model
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        return response()->json($comment); // Trả về thông tin bình luận
    }

    // Cập nhật một bình luận theo ID
    public function update(Request $request, $id)
    {
        $comment = Comment::getCommentById($id); // Gọi phương thức trong model
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'ticket_id' => 'sometimes|required|integer',
            'user_id' => 'sometimes|required|integer',
            'content' => 'sometimes|required|string|max:1000',
        ]);

        // Cập nhật bình luận
        $comment->updateComment($validatedData); // Gọi phương thức trong model
        return response()->json($comment);
    }

    // Xóa một bình luận theo ID
    public function destroy($id)
    {
        $comment = Comment::getCommentById($id); // Gọi phương thức trong model
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        // Xóa bình luận
        $comment->deleteComment(); // Gọi phương thức trong model
        return response()->json(['message' => 'Comment deleted']);
    }
}
