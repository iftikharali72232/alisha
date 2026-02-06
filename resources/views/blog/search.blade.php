@extends('layouts.blog')

@section('title', 'Search Results: ' . $query)
@section('meta_description', 'Search results for "' . $query . '" on Vision Sphere.')

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-white border-b" aria-label="Breadcrumb">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-rose-600 transition"><i class="fas fa-home"></i></a></li>
                <li><span class="mx-1">/</span></li>
                <li><a href="{{ route('blog.index') }}" class="hover:text-rose-600 transition">Blog</a></li>
                <li><span class="mx-1">/</span></li>
                <li class="text-gray-900 font-medium">Search: {{ $query }}</li>
            </ol>
        </div>
    </nav>

    <!-- Search Header -->
    <div class="bg-gradient-to-r from-rose-500 to-pink-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-search text-2xl text-white"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Search Results</h1>
            <p class="text-white/80">Results for "{{ $query }}"</p>
            <p class="text-white/60 mt-2">{{ $posts->total() }} {{ Str::plural('result', $posts->total()) }} found</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Search Form -->
        <div class="max-w-2xl mx-auto mb-12">
            <form action="{{ route('blog.search') }}" method="GET">
                <div class="relative">
                    <input type="text" name="q" value="{{ $query }}" placeholder="Search posts..." class="w-full pl-12 pr-4 py-4 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent text-lg">
                    <i class="fas fa-search absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 px-6 py-2 bg-rose-600 text-white rounded-full hover:bg-rose-700 transition">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                @if($posts->count() > 0)
                <div class="space-y-6">
                    @foreach($posts as $post)
                    <article class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition">
                        <div class="md:flex">
                            <a href="{{ route('blog.show', $post->slug) }}" class="md:w-1/3 flex-shrink-0">
                                <div class="relative overflow-hidden" style="padding-bottom: 75%;">
                                    @if($post->featured_image)
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition duration-300">
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
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-rose-600 hover:text-rose-700 text-sm font-medium">
                                    Read More <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-8">
                    {{ $posts->appends(['q' => $query])->links() }}
                </div>
                @else
                <div class="bg-white rounded-2xl p-12 text-center">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg mb-2">No results found for "{{ $query }}"</p>
                    <p class="text-gray-400">Try different keywords or check the spelling.</p>
                    <a href="{{ route('blog.index') }}" class="inline-flex items-center mt-6 text-rose-600 hover:text-rose-700">
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
