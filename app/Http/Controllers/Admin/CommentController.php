<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with(['post', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.comments.index', compact('comments'));
    }

    public function show(Comment $comment)
    {
        return view('admin.comments.show', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,spam',
        ]);

        $comment->update([
            'status' => $request->status,
            'approved' => $request->status === 'approved'
        ]);

        return back()->with('success', 'Comment status updated.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('admin.comments.index')->with('success', 'Comment deleted.');
    }

    public function approve(Comment $comment)
    {
        $comment->update([
            'status' => 'approved',
            'approved' => true
        ]);
        return back()->with('success', 'Comment approved.');
    }

    public function spam(Comment $comment)
    {
        $comment->update([
            'status' => 'spam',
            'approved' => false
        ]);
        return back()->with('success', 'Comment marked as spam.');
    }
}
