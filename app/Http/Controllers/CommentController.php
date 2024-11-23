<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    // Lấy danh sách tất cả các bình luận
    public function index(): JsonResponse
    {
        return response()->json(Comment::getAllComments());
    }

    // Tạo bình luận mới
    public function store(Request $request): JsonResponse
    {
        $comment = Comment::createComment($request->all()); // Gọi phương thức trong model
        if ($comment instanceof \Illuminate\Http\JsonResponse) {
            return $comment; // Trả về lỗi nếu có
        }

        return response()->json($comment, 201);
    }

    // Lấy thông tin của một bình luận theo ID
    public function show($id): JsonResponse
    {
        $comment = Comment::getCommentById($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        return response()->json($comment);
    }

    // Cập nhật một bình luận theo ID
    public function update(Request $request, $id): JsonResponse
    {
        $comment = Comment::getCommentById($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $comment->updateComment($request->all()); // Gọi phương thức trong model
        return response()->json($comment);
    }

    // Xóa một bình luận theo ID
    public function destroy($id): JsonResponse
    {
        $comment = Comment::getCommentById($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $comment->deleteComment(); // Gọi phương thức trong model
        return response()->json(['message' => 'Comment deleted']);
    }
}
