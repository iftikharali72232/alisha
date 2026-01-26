<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with(['post', 'user', 'parent', 'replies'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.comments.index', compact('comments'));
    }

    public function show(Comment $comment)
    {
        $comment->load(['post', 'user', 'parent', 'allReplies']);
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

    /**
     * Reply to a comment from admin panel
     */
    public function reply(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'post_id' => $comment->post_id,
            'parent_id' => $comment->id,
            'user_id' => auth()->id(),
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'content' => $validated['content'],
            'approved' => true,
            'status' => 'approved',
        ]);

        return back()->with('success', 'Reply posted successfully.');
    }
}
