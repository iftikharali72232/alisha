<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $stats = [
            'total_posts' => Post::where('user_id', $user->id)->count(),
            'published_posts' => Post::where('user_id', $user->id)->where('status', 'published')->count(),
            'draft_posts' => Post::where('user_id', $user->id)->where('status', 'draft')->count(),
        ];
        
        $recentPosts = Post::where('user_id', $user->id)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('user.dashboard', compact('stats', 'recentPosts'));
    }
}
