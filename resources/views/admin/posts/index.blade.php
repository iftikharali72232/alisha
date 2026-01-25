@extends('layouts.admin')

@section('title', 'Posts')
@section('page-title', 'All Posts')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Posts Management</h1>
            <p class="text-gray-600 mt-1">View and manage all blog posts</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center px-6 py-3 bg-rose-600 text-white text-sm font-medium rounded-lg hover:bg-rose-700 transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i>Create New Post
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Title</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Category</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Status</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Date</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($posts as $post)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-4 py-4 text-sm text-gray-900 font-medium">{{ $post->title }}</td>
                        <td class="px-4 py-4 text-sm text-gray-600">{{ $post->category->name ?? 'Uncategorized' }}</td>
                        <td class="px-4 py-4 text-sm">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                <i class="fas fa-{{ $post->status === 'published' ? 'check-circle' : 'edit' }} mr-1"></i>
                                {{ ucfirst($post->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-600">{{ $post->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-4 text-sm space-x-2">
                            <a href="{{ route('admin.posts.show', $post) }}" class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                                <i class="fas fa-eye mr-1"></i>View
                            </a>
                            <a href="{{ route('admin.posts.edit', $post) }}" class="inline-flex items-center px-3 py-1 text-xs font-medium text-rose-700 bg-rose-100 rounded-lg hover:bg-rose-200 transition-colors duration-200">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition-colors duration-200">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-newspaper text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 font-medium mb-4">No posts found</p>
                                <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white text-sm font-medium rounded-lg hover:bg-rose-700 transition-colors duration-200">
                                    <i class="fas fa-plus mr-2"></i>Create Your First Post
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection