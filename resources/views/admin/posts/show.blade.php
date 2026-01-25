@extends('layouts.admin')

@section('title', $post->title)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $post->title }}</h1>
            <p class="text-gray-600 mt-1">View post details</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.posts.edit', $post) }}" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>
                Edit Post
            </a>
            <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center px-4 py-2 bg-rose-100 text-rose-700 rounded-lg hover:bg-rose-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-8">
            <!-- Meta Information -->
            <div class="mb-6 pb-6 border-b border-gray-200 flex flex-wrap gap-3 items-center">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    <i class="fas fa-{{ $post->status === 'published' ? 'check-circle' : 'file-alt' }} mr-2"></i>
                    {{ ucfirst($post->status) }}
                </span>
                
                <div class="text-sm text-gray-600">
                    <i class="fas fa-folder text-rose-500 mr-1"></i>
                    {{ $post->category->name }}
                </div>

                <div class="text-sm text-gray-600">
                    <i class="fas fa-user text-rose-500 mr-1"></i>
                    By {{ $post->user->name }}
                </div>

                @if($post->published_at)
                <div class="text-sm text-gray-600">
                    <i class="fas fa-calendar text-rose-500 mr-1"></i>
                    {{ $post->published_at->format('M d, Y') }}
                </div>
                @endif
            </div>

            <!-- Excerpt -->
            @if($post->excerpt)
            <div class="mb-6 p-4 bg-rose-50 rounded-lg border border-rose-200">
                <h3 class="text-sm font-medium text-rose-900 mb-2">Excerpt</h3>
                <p class="text-gray-700">{{ $post->excerpt }}</p>
            </div>
            @endif

            <!-- Content -->
            <div class="prose prose-lg max-w-none">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Content</h2>
                <div class="text-gray-700 leading-relaxed">
                    {!! $post->content !!}
                </div>
            </div>

            <!-- Edit Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex gap-3">
                <a href="{{ route('admin.posts.edit', $post) }}" class="inline-flex items-center px-6 py-3 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-colors font-medium">
                    <i class="fas fa-edit mr-2"></i>
                    Edit This Post
                </a>
                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        <i class="fas fa-trash mr-2"></i>
                        Delete Post
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Post Information</h3>

            <!-- Stats -->
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Status</p>
                    <p class="font-medium text-gray-900">{{ ucfirst($post->status) }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-1">Category</p>
                    <p class="font-medium text-gray-900">{{ $post->category->name }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-1">Author</p>
                    <p class="font-medium text-gray-900">{{ $post->user->name }}</p>
                </div>

                @if($post->published_at)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Published Date</p>
                    <p class="font-medium text-gray-900">{{ $post->published_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
                @endif

                <div>
                    <p class="text-sm text-gray-600 mb-1">Created Date</p>
                    <p class="font-medium text-gray-900">{{ $post->created_at->format('M d, Y \a\t h:i A') }}</p>
                </div>

                @if($post->updated_at && $post->updated_at->ne($post->created_at))
                <div>
                    <p class="text-sm text-gray-600 mb-1">Last Updated</p>
                    <p class="font-medium text-gray-900">{{ $post->updated_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.posts.index') }}" class="w-full block text-center px-4 py-2 bg-rose-100 text-rose-700 rounded-lg hover:bg-rose-200 transition-colors font-medium">
                    <i class="fas fa-list mr-2"></i>
                    All Posts
                </a>
            </div>
        </div>
    </div>
</div>
@endsection