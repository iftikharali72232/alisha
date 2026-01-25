@extends('layouts.blog')

@section('title', $post->title)
@section('meta_description', Str::limit(strip_tags($post->content), 160))

@section('content')
<article>
    <!-- Featured Image -->
    @if($post->featured_image)
    <div class="relative h-96 md:h-[500px] bg-gray-900">
        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover opacity-90">
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-8">
            <div class="max-w-4xl mx-auto">
                @if($post->category)
                <a href="{{ route('blog.category', $post->category->slug) }}" class="inline-block bg-rose-600 text-white text-sm px-4 py-1 rounded-full mb-4 hover:bg-rose-700 transition">
                    {{ $post->category->name }}
                </a>
                @endif
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4">{{ $post->title }}</h1>
                <div class="flex items-center space-x-4 text-white/80">
                    <div class="flex items-center">
                        <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) . '&background=f43f5e&color=fff' }}" alt="{{ $post->user->name }}" class="w-10 h-10 rounded-full">
                        <span class="ml-2">{{ $post->user->name }}</span>
                    </div>
                    <span>•</span>
                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                    <span>•</span>
                    <span>{{ $post->comments->count() }} comments</span>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="bg-gradient-to-r from-rose-500 to-purple-600 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            @if($post->category)
            <a href="{{ route('blog.category', $post->category->slug) }}" class="inline-block bg-white/20 backdrop-blur-sm text-white text-sm px-4 py-1 rounded-full mb-4 hover:bg-white/30 transition">
                {{ $post->category->name }}
            </a>
            @endif
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ $post->title }}</h1>
            <div class="flex items-center justify-center space-x-4 text-white/80">
                <div class="flex items-center">
                    <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) . '&background=f43f5e&color=fff' }}" alt="{{ $post->user->name }}" class="w-10 h-10 rounded-full">
                    <span class="ml-2">{{ $post->user->name }}</span>
                </div>
                <span>•</span>
                <span>{{ $post->created_at->format('M d, Y') }}</span>
            </div>
        </div>
    </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Post Content -->
                <div class="bg-white rounded-2xl shadow-sm p-8 mb-8">
                    <div class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-a:text-rose-600 prose-a:no-underline hover:prose-a:underline">
                        {!! $post->content !!}
                    </div>
                    
                    <!-- Gallery Images -->
                    @if($post->gallery_images)
                    @php $galleryImages = json_decode($post->gallery_images, true); @endphp
                    @if(is_array($galleryImages) && count($galleryImages) > 0)
                    <div class="mt-8 pt-8 border-t">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Gallery</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($galleryImages as $image)
                            <a href="{{ asset('storage/' . $image) }}" target="_blank" class="block">
                                <img src="{{ asset('storage/' . $image) }}" alt="Gallery image" class="w-full h-40 object-cover rounded-lg hover:opacity-90 transition">
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endif
                    
                    <!-- Tags -->
                    @if($post->tags->count() > 0)
                    <div class="mt-8 pt-8 border-t">
                        <div class="flex items-center flex-wrap gap-2">
                            <span class="text-gray-600 font-medium">Tags:</span>
                            @foreach($post->tags as $tag)
                            <a href="{{ route('blog.tag', $tag->slug) }}" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-rose-100 hover:text-rose-600 transition">
                                #{{ $tag->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Author Box -->
                <div class="bg-white rounded-2xl shadow-sm p-8 mb-8">
                    <div class="flex items-start space-x-4">
                        <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) . '&background=f43f5e&color=fff&size=100' }}" alt="{{ $post->user->name }}" class="w-20 h-20 rounded-full">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $post->user->name }}</h3>
                            <p class="text-gray-600 mt-1">{{ $post->user->bio ?? 'Author at ' . \App\Models\Setting::get('site_name', 'VisionSphere – Explore your world of ideas and stories.') }}</p>
                            <div class="flex space-x-3 mt-3">
                                @if($post->user->facebook_url)
                                <a href="{{ $post->user->facebook_url }}" target="_blank" class="text-gray-400 hover:text-blue-600 transition">
                                    <i class="fab fa-facebook"></i>
                                </a>
                                @endif
                                @if($post->user->twitter_url)
                                <a href="{{ $post->user->twitter_url }}" target="_blank" class="text-gray-400 hover:text-sky-500 transition">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                @endif
                                @if($post->user->instagram_url)
                                <a href="{{ $post->user->instagram_url }}" target="_blank" class="text-gray-400 hover:text-pink-500 transition">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">
                        Comments ({{ $post->comments->where('status', 'approved')->count() }})
                    </h3>
                    
                    <!-- Comment Form -->
                    <form action="{{ route('blog.comment', $post->slug) }}" method="POST" class="mb-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                                <input type="text" name="name" id="name" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input type="email" name="email" id="email" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                            <input type="url" name="website" id="website" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                        </div>
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Comment *</label>
                            <textarea name="content" id="content" rows="4" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent"></textarea>
                        </div>
                        <button type="submit" class="px-6 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition">
                            Post Comment
                        </button>
                    </form>
                    
                    @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                    @endif
                    
                    <!-- Comments List -->
                    <div class="space-y-6">
                        @forelse($post->comments->where('status', 'approved') as $comment)
                        <div class="flex space-x-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->name) }}&background=f43f5e&color=fff" alt="{{ $comment->name }}" class="w-12 h-12 rounded-full flex-shrink-0">
                            <div class="flex-1">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-gray-900">{{ $comment->name }}</h4>
                                        <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-600">{{ $comment->content }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">No comments yet. Be the first to comment!</p>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                @include('blog.partials.sidebar', ['categories' => $categories, 'popularPosts' => $relatedPosts])
            </div>
        </div>
        
        <!-- Related Posts -->
        @if($relatedPosts->count() > 0)
        <section class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Related Posts</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($relatedPosts as $related)
                <article class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition group">
                    <a href="{{ route('blog.show', $related->slug) }}" class="block">
                        <div class="relative overflow-hidden" style="padding-bottom: 60%;">
                            @if($related->featured_image)
                            <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                            <div class="absolute inset-0 bg-gradient-to-br from-rose-400 to-purple-500"></div>
                            @endif
                        </div>
                    </a>
                    <div class="p-6">
                        <span class="text-sm text-gray-500">{{ $related->created_at->format('M d, Y') }}</span>
                        <h3 class="text-lg font-semibold text-gray-900 mt-2 line-clamp-2">
                            <a href="{{ route('blog.show', $related->slug) }}" class="hover:text-rose-600 transition">{{ $related->title }}</a>
                        </h3>
                    </div>
                </article>
                @endforeach
            </div>
        </section>
        @endif
    </div>
</article>
@endsection

@section('styles')
<style>
    .prose img {
        border-radius: 0.75rem;
    }
    .prose h2 {
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    .prose p {
        margin-bottom: 1.25rem;
    }
    .prose blockquote {
        border-left-color: #f43f5e;
        background-color: #fdf2f8;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
    }
</style>
@endsection
