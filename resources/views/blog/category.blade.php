@extends('layouts.blog')

@section('title', $category->name)

@section('content')
    <!-- Category Header -->
    <div class="bg-gradient-to-r from-rose-500 to-purple-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            @if($category->image)
            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-24 h-24 rounded-2xl object-cover mx-auto mb-4 border-4 border-white/30">
            @else
            <div class="w-24 h-24 rounded-2xl bg-white/20 backdrop-blur-sm mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-folder text-4xl text-white"></i>
            </div>
            @endif
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $category->name }}</h1>
            @if($category->description)
            <p class="text-white/80 max-w-2xl mx-auto">{{ $category->description }}</p>
            @endif
            <p class="text-white/60 mt-4">{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }} in this category</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($posts as $post)
                    <article class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition group">
                        <a href="{{ route('blog.show', $post->slug) }}" class="block">
                            <div class="relative overflow-hidden" style="padding-bottom: 60%;">
                                @if($post->featured_image)
                                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                <div class="absolute inset-0 bg-gradient-to-br from-rose-400 to-purple-500"></div>
                                @endif
                            </div>
                        </a>
                        <div class="p-6">
                            <div class="flex items-center space-x-2 text-sm text-gray-500 mb-3">
                                <span>{{ $post->created_at->format('M d, Y') }}</span>
                                <span>•</span>
                                <span>{{ $post->comments_count ?? 0 }} comments</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 line-clamp-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-rose-600 transition">{{ $post->title }}</a>
                            </h3>
                            <p class="text-gray-600 line-clamp-2 mb-4">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) . '&background=f43f5e&color=fff' }}" alt="{{ $post->user->name }}" class="w-8 h-8 rounded-full">
                                    <span class="ml-2 text-sm text-gray-700">{{ $post->user->name }}</span>
                                </div>
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-rose-600 hover:text-rose-700">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
                @else
                <div class="bg-white rounded-2xl p-12 text-center">
                    <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">No posts in this category yet.</p>
                    <a href="{{ route('blog.index') }}" class="inline-flex items-center mt-4 text-rose-600 hover:text-rose-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Blog
                    </a>
                </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                @include('blog.partials.sidebar', ['categories' => $categories, 'popularPosts' => $popularPosts])
            </div>
        </div>
    </div>
@endsection
