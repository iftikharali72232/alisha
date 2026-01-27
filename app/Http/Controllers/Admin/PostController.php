<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Post::with('category', 'user');
        
        if (!auth()->user()->hasPermission('view-posts')) {
            $query->where('user_id', auth()->id());
        }
        
        $posts = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'status' => 'sometimes|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
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
            'title' => $request->title,
            'slug' => \Str::slug($request->title) . '-' . time(),
            'content' => $request->content,
            'excerpt' => $request->excerpt,
            'status' => auth()->user()->is_admin ? ($request->status ?? 'draft') : 'draft',
            'featured_image' => $featuredImagePath,
            'gallery_images' => $galleryImages,
            'is_featured' => $request->has('is_featured'),
            'category_id' => $request->category_id,
            'user_id' => auth()->id() ?? 1,
            'published_at' => (auth()->user()->is_admin && $request->status === 'published') ? now() : null,
        ]);

        // Attach tags
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with('category', 'user', 'tags')->findOrFail($id);
        
        if (!auth()->user()->is_admin && $post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::with('tags')->findOrFail($id);
        
        if (!auth()->user()->is_admin && $post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);
        
        if (!auth()->user()->is_admin && $post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'status' => 'sometimes|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $updateData = [
            'title' => $request->title,
            'slug' => \Str::slug($request->title) . '-' . $post->id,
            'content' => $request->content,
            'excerpt' => $request->excerpt,
            'category_id' => $request->category_id,
            'is_featured' => $request->has('is_featured'),
        ];

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $updateData['featured_image'] = $request->file('featured_image')->store('posts/featured', 'public');
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            $existingGallery = $post->gallery_images ?? [];
            foreach ($request->file('gallery_images') as $image) {
                $existingGallery[] = $image->store('posts/gallery', 'public');
            }
            $updateData['gallery_images'] = $existingGallery;
        }

        // Handle remove gallery images
        if ($request->has('remove_gallery_images')) {
            $currentGallery = $post->gallery_images ?? [];
            foreach ($request->remove_gallery_images as $imagePath) {
                Storage::disk('public')->delete($imagePath);
                $currentGallery = array_filter($currentGallery, fn($img) => $img !== $imagePath);
            }
            $updateData['gallery_images'] = array_values($currentGallery);
        }

        if (auth()->user()->is_admin && $request->has('status')) {
            $updateData['status'] = $request->status;
            if ($request->status === 'published' && !$post->published_at) {
                $updateData['published_at'] = now();
            } elseif ($request->status !== 'published') {
                $updateData['published_at'] = null;
            }
        }

        $post->update($updateData);

        // Sync tags
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->detach();
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Toggle post status (for admin quick actions)
     */
    public function toggleStatus(Post $post)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $post->update([
            'status' => $post->status === 'published' ? 'draft' : 'published',
            'published_at' => $post->status === 'published' ? null : now(),
        ]);

        return back()->with('success', 'Post status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        
        if (!auth()->user()->is_admin && $post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
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
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }

    /**
     * Upload image for TinyMCE editor
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
