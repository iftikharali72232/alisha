@extends('layouts.admin')

@section('title', 'Edit Gallery Image')
@section('page-title', 'Edit Gallery Image')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.galleries.index') }}" class="inline-flex items-center text-gray-600 hover:text-rose-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Gallery
        </a>
    </div>

    <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-image text-rose-500 mr-2"></i>Image Details
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $gallery->title) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        placeholder="Image title" required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        placeholder="Brief description...">{{ old('description', $gallery->description) }}</textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                    <img src="{{ Str::startsWith($gallery->image, 'http') ? $gallery->image : Storage::url($gallery->image) }}" alt="{{ $gallery->title }}" class="w-full h-48 object-cover rounded-lg">
                </div>
                
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Change Image <span class="text-gray-400">(optional)</span></label>
                    <input type="file" name="image" id="image" accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <input type="text" name="category" id="category" value="{{ old('category', $gallery->category) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                            placeholder="e.g., Nature, Portrait">
                    </div>
                    
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                        <input type="number" name="order" id="order" value="{{ old('order', $gallery->order) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                            min="0">
                    </div>
                </div>
                
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $gallery->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-rose-600 border-gray-300 rounded focus:ring-rose-500">
                        <span class="ml-2 text-sm text-gray-700">Visible in gallery</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
            
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.galleries.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition font-medium">
                    <i class="fas fa-save mr-2"></i>Update Image
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
