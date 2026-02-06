@extends('layouts.blog')

@section('title', 'Posts tagged: ' . $tag->name)
@section('meta_description', 'Browse all articles tagged with ' . $tag->name . ' on Vision Sphere. Find related content and insights.')
@section('canonical_url', route('blog.tag', $tag->slug))

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-white border-b" aria-label="Breadcrumb">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-rose-600 transition"><i class="fas fa-home"></i></a></li>
                <li><span class="mx-1">/</span></li>
                <li><a href="{{ route('blog.index') }}" class="hover:text-rose-600 transition">Blog</a></li>
                <li><span class="mx-1">/</span></li>
                <li class="text-gray-900 font-medium">#{{ $tag->name }}</li>
            </ol>
        </div>
    </nav>

    <!-- Tag Header -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-hashtag text-2xl text-white"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $tag->name }}</h1>
            <p class="text-white/60">{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }} with this tag</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                @if($posts->count() > 0)
                <div class="space-y-8">
                    @foreach($posts as $post)
                    <article class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition">
                        <div class="md:flex">
                            <a href="{{ route('blog.show', $post->slug) }}" class="md:w-1/3 flex-shrink-0">
                                <div class="relative overflow-hidden" style="padding-bottom: 75%;">
                                    @if($post->featured_image)
                                    @php
                                        $featuredImageUrl = Str::startsWith($post->featured_image, 'http') ? $post->featured_image : Storage::url($post->featured_image);
                                    @endphp
                                    <img src="{{ $featuredImageUrl }}" alt="{{ $post->title }}" class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition duration-300">
                                    @else
                                    <div class="absolute inset-0 bg-gradient-to-br from-rose-400 to-purple-500"></div>
                                    @endif
                                </div>
                            </a>
                            <div class="p-6 md:w-2/3">
                                <div class="flex items-center space-x-2 text-sm text-gray-500 mb-3">
                                    @if($post->category)
                                    <a href="{{ route('blog.category', $post->category->slug) }}" class="text-rose-600 hover:text-rose-700">{{ $post->category->name }}</a>
                                    <span>•</span>
                                    @endif
                                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-rose-600 transition">{{ $post->title }}</a>
                                </h3>
                                <p class="text-gray-600 line-clamp-2 mb-4">{{ Str::limit(strip_tags($post->content), 150) }}</p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="w-8 h-8 rounded-full">
                                        <span class="ml-2 text-sm text-gray-700">{{ $post->user->name }}</span>
                                    </div>
                                    <a href="{{ route('blog.show', $post->slug) }}" class="text-rose-600 hover:text-rose-700 text-sm font-medium">
                                        Read More <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
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
                    <i class="fas fa-hashtag text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">No posts with this tag yet.</p>
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
