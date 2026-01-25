<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Page;
use App\Models\Slider;
use App\Models\Gallery;
use App\Models\Setting;
use App\Models\Comment;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $featuredPosts = Post::where('status', 'published')
            ->where('is_featured', true)
            ->with('category', 'user')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        $posts = Post::where('status', 'published')
            ->with('category', 'user', 'tags')
            ->orderBy('created_at', 'desc')
            ->paginate(Setting::get('posts_per_page', 10));
            
        $categories = Category::withCount(['posts' => function($query) {
            $query->where('status', 'published');
        }])->get();
        
        $sliders = Slider::active()->orderBy('order')->get();
        
        $popularPosts = Post::where('status', 'published')
            ->withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->take(5)
            ->get();
        
        return view('blog.index', compact('featuredPosts', 'posts', 'categories', 'sliders', 'popularPosts'));
    }
    
    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->with(['category', 'user', 'tags', 'comments' => function($query) {
                $query->where('status', 'approved')->orderBy('created_at', 'desc');
            }])
            ->firstOrFail();
            
        $relatedPosts = Post::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->with('category', 'user')
            ->take(3)
            ->get();
            
        $categories = Category::withCount(['posts' => function($query) {
            $query->where('status', 'published');
        }])->get();
        
        return view('blog.show', compact('post', 'relatedPosts', 'categories'));
    }
    
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $posts = Post::where('status', 'published')
            ->where('category_id', $category->id)
            ->with('category', 'user', 'tags')
            ->orderBy('created_at', 'desc')
            ->paginate(Setting::get('posts_per_page', 10));
            
        $categories = Category::withCount(['posts' => function($query) {
            $query->where('status', 'published');
        }])->get();
        
        $popularPosts = Post::where('status', 'published')
            ->withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->take(5)
            ->get();
        
        return view('blog.category', compact('category', 'posts', 'categories', 'popularPosts'));
    }
    
    public function tag($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        
        $posts = $tag->posts()
            ->where('status', 'published')
            ->with('category', 'user', 'tags')
            ->orderBy('created_at', 'desc')
            ->paginate(Setting::get('posts_per_page', 10));
            
        $categories = Category::withCount(['posts' => function($query) {
            $query->where('status', 'published');
        }])->get();
        
        $popularPosts = Post::where('status', 'published')
            ->withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->take(5)
            ->get();
        
        return view('blog.tag', compact('tag', 'posts', 'categories', 'popularPosts'));
    }
    
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $posts = Post::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->with('category', 'user', 'tags')
            ->orderBy('created_at', 'desc')
            ->paginate(Setting::get('posts_per_page', 10));
            
        $categories = Category::withCount(['posts' => function($q) {
            $q->where('status', 'published');
        }])->get();
        
        $popularPosts = Post::where('status', 'published')
            ->withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->take(5)
            ->get();
        
        return view('blog.search', compact('query', 'posts', 'categories', 'popularPosts'));
    }
    
    public function page($slug)
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        return view('blog.page', compact('page'));
    }
    
    public function gallery()
    {
        $galleries = Gallery::active()->orderBy('order')->paginate(12);
        $categories = $galleries->pluck('category')->filter()->unique()->values();
        
        return view('blog.gallery', compact('galleries', 'categories'));
    }
    
    public function storeComment(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string|max:1000',
        ]);
        
        Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'content' => $validated['content'],
            'approved' => false,
            'status' => 'pending',
        ]);
        
        return back()->with('success', 'Your comment has been submitted and is awaiting moderation.');
    }
    
    public function about()
    {
        return view('blog.about');
    }
    
    public function contact()
    {
        return view('blog.contact');
    }
    
    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);
        
        // Here you would typically send an email
        // Mail::to(Setting::get('contact_email'))->send(new ContactMessage($validated));
        
        return back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
