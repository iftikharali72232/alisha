<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Shop;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

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

    public function profile()
    {
        $user = auth()->user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profile-images', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function settings()
    {
        $user = auth()->user();
        return view('user.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'marketing_emails' => 'boolean',
        ]);

        $user->update([
            'email_notifications' => $request->boolean('email_notifications'),
            'marketing_emails' => $request->boolean('marketing_emails'),
        ]);

        return back()->with('success', 'Settings updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    // Post Management Methods
    public function posts()
    {
        $posts = Post::where('user_id', auth()->id())
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('user.posts.index', compact('posts'));
    }

    public function createPost()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('user.posts.create', compact('categories', 'tags'));
    }

    public function storePost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'excerpt' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'sometimes|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Handle featured image upload
        $featuredImagePath = null;
        if ($request->hasFile('featured_image')) {
            $featuredImagePath = $request->file('featured_image')->store('posts/featured', 'public');
        }

        // Handle gallery images upload
        $galleryImages = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('posts/gallery', 'public');
            }
        }

        $post = Post::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . time(),
            'content' => $validated['content'],
            'excerpt' => $validated['excerpt'] ?? null,
            'status' => auth()->user()->is_admin ? ($validated['status'] ?? 'draft') : 'draft',
            'featured_image' => $featuredImagePath,
            'gallery_images' => $galleryImages,
            'is_featured' => auth()->user()->is_admin ? $request->has('is_featured') : false,
            'category_id' => $validated['category_id'],
            'user_id' => auth()->id(),
            'published_at' => (auth()->user()->is_admin && ($validated['status'] ?? 'draft') === 'published') ? now() : null,
        ]);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('user.posts.index')->with('success', 'Post created successfully.');
    }

    public function showPost(Post $post)
    {
        // Ensure user owns the post
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        $post->load('category', 'tags', 'comments.user');
        return view('user.posts.show', compact('post'));
    }

    public function editPost(Post $post)
    {
        // Ensure user owns the post
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = Category::all();
        $tags = Tag::all();
        return view('user.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function updatePost(Request $request, Post $post)
    {
        // Ensure user owns the post
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'excerpt' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'sometimes|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'remove_gallery_images' => 'nullable|array',
            'remove_gallery_images.*' => 'string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $updateData = [
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . $post->id,
            'content' => $validated['content'],
            'excerpt' => $validated['excerpt'] ?? null,
            'category_id' => $validated['category_id'],
            'is_featured' => auth()->user()->is_admin ? $request->has('is_featured') : $post->is_featured,
        ];

        // Only admin can change status
        if (auth()->user()->is_admin && isset($validated['status'])) {
            $updateData['status'] = $validated['status'];
            $updateData['published_at'] = $validated['status'] === 'published' ? ($post->published_at ?? now()) : null;
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $updateData['featured_image'] = $request->file('featured_image')->store('posts/featured', 'public');
        }

        // Handle remove gallery images
        $currentGallery = $post->gallery_images ?? [];
        if (!empty($validated['remove_gallery_images'])) {
            foreach ($validated['remove_gallery_images'] as $imagePath) {
                Storage::disk('public')->delete($imagePath);
                $currentGallery = array_values(array_filter($currentGallery, fn($p) => $p !== $imagePath));
            }
        }

        // Handle gallery images upload (append)
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $currentGallery[] = $image->store('posts/gallery', 'public');
            }
        }
        $updateData['gallery_images'] = $currentGallery;

        $post->update($updateData);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('user.posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroyPost(Post $post)
    {
        // Ensure user owns the post
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        // Delete images
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        if ($post->gallery_images) {
            foreach ($post->gallery_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $post->delete();

        return redirect()->route('user.posts.index')->with('success', 'Post deleted successfully!');
    }

    public function togglePostStatus(Post $post)
    {
        // Ensure user owns the post
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        // Non-admin users: toggle only their own post
        $nextStatus = $post->status === 'published' ? 'draft' : 'published';
        $post->update([
            'status' => $nextStatus,
            'published_at' => $nextStatus === 'published' ? ($post->published_at ?? now()) : null,
        ]);

        return back()->with('success', 'Post status updated successfully.');
    }

    /**
     * Upload image for TinyMCE editor (User)
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('posts/content', 'public');
            $url = '/storage/' . $path;

            return response()->json(['location' => $url]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
