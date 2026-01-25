@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<!-- Welcome Section -->
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome back, {{ auth()->user()->name }}!</h2>
    <p class="text-gray-600">Here's what's happening with your blog today.</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @if(auth()->user()->is_admin)
        <!-- Admin Stats -->
        <div class="bg-white rounded-xl shadow-sm border border-rose-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Posts</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_posts'] }}</p>
                    <p class="text-xs text-rose-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+12% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-rose-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-newspaper text-rose-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-rose-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Users</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['active_users'] }}</p>
                    <p class="text-xs text-pink-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+8% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-pink-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-rose-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Categories</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_categories'] }}</p>
                    <p class="text-xs text-purple-600 mt-1">
                        <i class="fas fa-minus mr-1"></i>No change
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-folder text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-rose-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Comments</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_comments'] }}</p>
                    <p class="text-xs text-rose-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+24% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-rose-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-comments text-rose-600 text-xl"></i>
                </div>
            </div>
        </div>
    @else
        <!-- User Stats -->
        <div class="bg-white rounded-xl shadow-sm border border-rose-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">My Posts</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['my_posts'] ?? 0 }}</p>
                    <p class="text-xs text-rose-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>Active writing
                    </p>
                </div>
                <div class="w-12 h-12 bg-rose-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-newspaper text-rose-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-rose-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Published</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['my_published_posts'] ?? 0 }}</p>
                    <p class="text-xs text-pink-600 mt-1">
                        <i class="fas fa-check-circle mr-1"></i>Live on blog
                    </p>
                </div>
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-pink-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-rose-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Drafts</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['my_draft_posts'] ?? 0 }}</p>
                    <p class="text-xs text-purple-600 mt-1">
                        <i class="fas fa-edit mr-1"></i>Work in progress
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-edit text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Account Status</p>
                    <p class="text-3xl font-bold text-gray-900">{{ auth()->user()->status == 1 ? 'Active' : 'Pending' }}</p>
                    <p class="text-xs {{ auth()->user()->status == 1 ? 'text-green-600' : 'text-yellow-600' }} mt-1">
                        <i class="fas fa-{{ auth()->user()->status == 1 ? 'check-circle' : 'clock' }} mr-1"></i>{{ auth()->user()->status == 1 ? 'Verified' : 'Awaiting approval' }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user text-gray-600 text-xl"></i>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Recent Activity / Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Posts -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Recent Posts</h3>
            <a href="{{ route('admin.posts.index') }}" class="text-rose-600 hover:text-rose-800 text-sm font-medium">View all</a>
        </div>
        <div class="space-y-4">
            @if(isset($recentPosts) && $recentPosts->count() > 0)
                @foreach($recentPosts->take(5) as $post)
                <div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="w-10 h-10 bg-gradient-to-r from-rose-500 to-pink-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-file-alt text-white text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $post->title }}</p>
                        <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($post->status) }}
                    </span>
                </div>
                @endforeach
            @else
                <div class="text-center py-8">
                    <i class="fas fa-newspaper text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500 mb-4">No posts yet</p>
                    <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white text-sm font-medium rounded-lg hover:bg-rose-700 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Create your first post
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-rose-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('admin.posts.create') }}" class="flex flex-col items-center p-4 bg-rose-50 rounded-lg hover:bg-rose-100 transition-colors duration-200 group">
                <div class="w-12 h-12 bg-rose-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-rose-200 transition-colors duration-200">
                    <i class="fas fa-plus text-rose-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">New Post</span>
            </a>
            <a href="{{ route('admin.posts.index') }}" class="flex flex-col items-center p-4 bg-pink-50 rounded-lg hover:bg-pink-100 transition-colors duration-200 group">
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-pink-200 transition-colors duration-200">
                    <i class="fas fa-list text-pink-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">Manage Posts</span>
            </a>
            @if(auth()->user()->is_admin)
            <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-200 group">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-purple-200 transition-colors duration-200">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">Users</span>
            </a>
            <a href="{{ route('admin.settings') }}" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors duration-200 group">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-yellow-200 transition-colors duration-200">
                    <i class="fas fa-cog text-yellow-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">Settings</span>
            </a>
            @else
            <a href="{{ route('admin.profile') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-200 group">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-purple-200 transition-colors duration-200">
                    <i class="fas fa-user text-purple-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">Profile</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors duration-200 group">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-yellow-200 transition-colors duration-200">
                    <i class="fas fa-folder text-yellow-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">Categories</span>
            </a>
            @endif
        </div>
    </div>
</div>

@if(auth()->user()->is_admin)
<!-- Additional Admin Sections -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
    <!-- Pending Comments -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Pending Comments</h3>
            <a href="{{ route('admin.comments.index') }}" class="text-rose-600 hover:text-rose-800 text-sm">View all</a>
        </div>
        <div class="text-center py-6">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <span class="text-2xl font-bold text-yellow-600">{{ $stats['pending_comments'] ?? 0 }}</span>
            </div>
            <p class="text-gray-600 text-sm">Comments awaiting moderation</p>
        </div>
    </div>
    
    <!-- Featured Posts -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Featured Posts</h3>
            <a href="{{ route('admin.posts.index') }}" class="text-rose-600 hover:text-rose-800 text-sm">Manage</a>
        </div>
        <div class="text-center py-6">
            <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <span class="text-2xl font-bold text-rose-600">{{ $stats['featured_posts'] ?? 0 }}</span>
            </div>
            <p class="text-gray-600 text-sm">Posts marked as featured</p>
        </div>
    </div>
    
    <!-- Total Tags -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Tags</h3>
            <a href="{{ route('admin.tags.index') }}" class="text-rose-600 hover:text-rose-800 text-sm">View all</a>
        </div>
        <div class="text-center py-6">
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <span class="text-2xl font-bold text-purple-600">{{ $stats['total_tags'] ?? 0 }}</span>
            </div>
            <p class="text-gray-600 text-sm">Total tags in system</p>
        </div>
    </div>
</div>
@endif
@endsection
