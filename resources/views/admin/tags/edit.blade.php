@extends('layouts.admin')

@section('title', 'Edit Tag')
@section('page-title', 'Edit Tag')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.tags.index') }}" class="inline-flex items-center text-rose-600 hover:text-rose-700">
        <i class="fas fa-arrow-left mr-2"></i>Back to Tags
    </a>
</div>

<div class="max-w-md">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Edit Tag: {{ $tag->name }}</h2>
        
        <form action="{{ route('admin.tags.update', $tag) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tag Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $tag->name) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                    placeholder="Enter tag name" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition font-medium">
                    <i class="fas fa-save mr-2"></i>Update Tag
                </button>
                <a href="{{ route('admin.tags.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
