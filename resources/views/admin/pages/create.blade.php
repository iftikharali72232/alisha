@extends('layouts.admin')

@section('title', 'Create Page')
@section('page-title', 'Create Page')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center text-gray-600 hover:text-rose-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Pages
        </a>
    </div>

    <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-file-alt text-rose-500 mr-2"></i>Page Content
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Page Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        placeholder="Enter page title" required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                        Slug <span class="text-gray-400">(leave empty to auto-generate)</span>
                    </label>
                    <div class="flex items-center">
                        <span class="px-3 py-2 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg text-gray-500">/page/</span>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                            placeholder="about-us">
                    </div>
                    @error('slug')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content *</label>
                    <textarea name="content" id="content" rows="15"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent tinymce"
                        placeholder="Write your page content here...">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-search text-rose-500 mr-2"></i>SEO Settings
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        placeholder="Page title for search engines">
                </div>
                
                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        placeholder="Brief description for search engines...">{{ old('meta_description') }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-cog text-rose-500 mr-2"></i>Page Settings
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
                    <input type="file" name="featured_image" id="featured_image" accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                </div>
                
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                    <input type="number" name="order" id="order" value="{{ old('order', 0) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        min="0">
                </div>
            </div>
            
            <div class="mt-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-rose-600 border-gray-300 rounded focus:ring-rose-500">
                    <span class="ml-2 text-sm text-gray-700">Publish this page</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('admin.pages.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition font-medium">
                <i class="fas fa-save mr-2"></i>Create Page
            </button>
        </div>
    </form>
</div>

<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '.tinymce',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        height: 400,
        menubar: false,
        skin: 'oxide',
        content_css: 'default'
    });
</script>
@endsection
