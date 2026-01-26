@extends('layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Categories Management</h1>
        <p class="text-gray-600 mt-1">Organize your blog posts with categories</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-6 py-3 bg-rose-600 text-white text-sm font-medium rounded-lg hover:bg-rose-700 transition-colors duration-200">
        <i class="fas fa-plus mr-2"></i>Add Category
    </a>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-800 rounded-lg">
    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Image</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Name</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Slug</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Posts</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4">
                        @if($category->image)
                            <img src="{{ Str::startsWith($category->image, 'http') ? $category->image : Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-12 h-12 rounded-lg object-cover">
                        @else
                            <div class="w-12 h-12 rounded-lg bg-rose-100 flex items-center justify-center">
                                <i class="fas fa-folder text-rose-500"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-medium text-gray-900">{{ $category->name }}</span>
                        @if($category->description)
                            <p class="text-sm text-gray-500 truncate max-w-xs">{{ $category->description }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $category->slug }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $category->posts_count }} posts
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center px-3 py-1 text-xs font-medium text-rose-700 bg-rose-100 rounded-lg hover:bg-rose-200 transition-colors duration-200">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
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
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 font-medium mb-4">No categories found</p>
                            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white text-sm font-medium rounded-lg hover:bg-rose-700 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>Create Your First Category
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($categories->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection
