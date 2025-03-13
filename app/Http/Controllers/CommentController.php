<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with(['user', 'product'])->latest()->paginate(10);
        return view('admin.comments.index', compact('comments'));
    }

    public function delete($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return redirect()->back()->with('success', 'Xóa bình luận thành công!');
    }
}
