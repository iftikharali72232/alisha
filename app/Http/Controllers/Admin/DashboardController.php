<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->is_admin) {
            // Admin stats
            $stats = [
                'total_posts' => Post::count(),
                'published_posts' => Post::where('status', 'published')->count(),
                'draft_posts' => Post::where('status', 'draft')->count(),
                'total_users' => User::count(),
                'active_users' => User::where('status', 1)->count(),
                'inactive_users' => User::where('status', 0)->count(),
                'total_categories' => Category::count(),
                'total_comments' => Comment::count(),
                'pending_comments' => Comment::where('status', 'pending')->count(),
                'featured_posts' => Post::where('is_featured', true)->count(),
                'total_tags' => Tag::count(),
            ];
            
            // Recent posts
            $recentPosts = Post::with('category', 'user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            // Regular user stats
            $stats = [
                'my_posts' => Post::where('user_id', $user->id)->count(),
                'my_published_posts' => Post::where('user_id', $user->id)->where('status', 'published')->count(),
                'my_draft_posts' => Post::where('user_id', $user->id)->where('status', 'draft')->count(),
            ];
            
            // Recent posts for user
            $recentPosts = Post::where('user_id', $user->id)
                ->with('category')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }
        
        return view('admin.dashboard', compact('stats', 'recentPosts'));
    }
}
