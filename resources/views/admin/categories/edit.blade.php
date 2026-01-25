@extends('layouts.admin')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center text-rose-600 hover:text-rose-700">
        <i class="fas fa-arrow-left mr-2"></i>Back to Categories
    </a>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Edit Category: {{ $category->name }}</h2>
        
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                    placeholder="Enter category name" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                    placeholder="Optional description">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category Image</label>
                
                @if($category->image)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                    <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-32 h-32 object-cover rounded-lg">
                </div>
                @endif
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-rose-400 transition cursor-pointer" onclick="document.getElementById('image').click()">
                    <input type="file" name="image" id="image" accept="image/*" class="hidden">
                    <div id="imagePreview">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-600">{{ $category->image ? 'Change image' : 'Click to upload image' }}</p>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                    </div>
                </div>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition font-medium">
                    <i class="fas fa-save mr-2"></i>Update Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').innerHTML = `
                <img src="${e.target.result}" class="w-32 h-32 object-cover rounded-lg mx-auto">
                <p class="text-sm text-gray-600 mt-2">${file.name}</p>
            `;
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
