@extends('layouts.admin')

@section('title', 'Tags')
@section('page-title', 'Tags')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tags Management</h1>
        <p class="text-gray-600 mt-1">Manage tags for your blog posts</p>
    </div>
    <a href="{{ route('admin.tags.create') }}" class="inline-flex items-center px-6 py-3 bg-rose-600 text-white text-sm font-medium rounded-lg hover:bg-rose-700 transition-colors duration-200">
        <i class="fas fa-plus mr-2"></i>Add Tag
    </a>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($tags as $tag)
        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-rose-100 text-rose-800">
                    <i class="fas fa-tag mr-1"></i>{{ $tag->name }}
                </span>
                <span class="text-xs text-gray-500">{{ $tag->posts_count }} posts</span>
            </div>
            <div class="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition">
                <a href="{{ route('admin.tags.edit', $tag) }}" class="text-rose-600 hover:text-rose-700 text-sm">
                    <i class="fas fa-edit"></i>
                </a>
                <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" class="inline" onsubmit="return confirm('Delete this tag?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-700 text-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <i class="fas fa-tags text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 font-medium mb-4">No tags found</p>
            <a href="{{ route('admin.tags.create') }}" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white text-sm font-medium rounded-lg hover:bg-rose-700 transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>Create Your First Tag
            </a>
        </div>
        @endforelse
    </div>
    
    @if($tags->hasPages())
    <div class="mt-6 pt-4 border-t border-gray-200">
        {{ $tags->links() }}
    </div>
    @endif
</div>
@endsection
