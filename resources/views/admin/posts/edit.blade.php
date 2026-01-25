@extends('layouts.admin')

@section('title', 'Edit Post')
@section('page-title', 'Edit Post')

@section('content')
<form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" id="postForm">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Title -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">
                    Post Title <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="{{ old('title', $post->title) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent transition text-lg"
                    placeholder="Enter an engaging title..."
                    required
                >
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content Editor -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <label for="content" class="block text-sm font-semibold text-gray-900 mb-2">
                    Content <span class="text-red-500">*</span>
                </label>
                <textarea 
                    name="content" 
                    id="content"
                    class="tinymce-editor"
                >{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Excerpt -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <label for="excerpt" class="block text-sm font-semibold text-gray-900 mb-2">
                    Excerpt (Summary)
                </label>
                <textarea 
                    name="excerpt" 
                    id="excerpt"
                    rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent transition"
                    placeholder="Brief summary of your post (optional)..."
                >{{ old('excerpt', $post->excerpt) }}</textarea>
                @error('excerpt')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Gallery Images -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-images text-rose-500 mr-2"></i>
                    Gallery Images
                </label>
                
                <!-- Existing Gallery Images -->
                @if($post->gallery_images && count($post->gallery_images) > 0)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Current Gallery Images:</p>
                    <div class="grid grid-cols-4 gap-4" id="existingGallery">
                        @foreach($post->gallery_images as $image)
                        <div class="relative group" id="gallery-{{ $loop->index }}">
                            <img src="{{ Storage::url($image) }}" class="w-full h-24 object-cover rounded-lg">
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center rounded-lg">
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="remove_gallery_images[]" value="{{ $image }}" class="sr-only">
                                    <span class="text-white text-xs bg-red-500 px-2 py-1 rounded"><i class="fas fa-trash"></i> Remove</span>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-rose-400 transition" id="galleryDropzone">
                    <input type="file" name="gallery_images[]" id="gallery_images" multiple accept="image/*" class="hidden">
                    <div class="space-y-2">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                        <p class="text-gray-600">Add more images - <button type="button" onclick="document.getElementById('gallery_images').click()" class="text-rose-600 hover:underline">browse</button></p>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF, WEBP up to 2MB each</p>
                    </div>
                </div>
                <div id="galleryPreview" class="grid grid-cols-4 gap-4 mt-4"></div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Publish Box -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-paper-plane text-rose-500 mr-2"></i>
                    Update Post
                </h3>
                
                @if(auth()->user()->is_admin)
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                        <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $post->is_featured) ? 'checked' : '' }} class="w-5 h-5 text-rose-600 border-gray-300 rounded focus:ring-rose-500">
                        <span class="text-sm text-gray-700">Featured Post</span>
                    </label>
                </div>
                @else
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-4">
                    <p class="text-sm text-gray-700">
                        <span class="font-medium">Current Status:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-2 {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($post->status) }}
                        </span>
                    </p>
                </div>
                @endif

                <div class="space-y-3">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition font-medium">
                        <i class="fas fa-save mr-2"></i>
                        Update Post
                    </button>
                    <a href="{{ route('admin.posts.show', $post) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                        <i class="fas fa-eye mr-2"></i>Preview
                    </a>
                    <a href="{{ route('admin.posts.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </div>

            <!-- Post Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Post Info</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Author:</span>
                        <span class="font-medium">{{ $post->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Created:</span>
                        <span class="font-medium">{{ $post->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($post->published_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Published:</span>
                        <span class="font-medium">{{ $post->published_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Category -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-folder text-rose-500 mr-2"></i>
                    Category
                </h3>
                <select name="category_id" id="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tags -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-tags text-rose-500 mr-2"></i>
                    Tags
                </h3>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @php $selectedTags = old('tags', $post->tags->pluck('id')->toArray()); @endphp
                    @forelse($tags as $tag)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}
                                class="w-4 h-4 text-rose-600 border-gray-300 rounded focus:ring-rose-500">
                            <span class="text-sm text-gray-700">{{ $tag->name }}</span>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500">No tags available</p>
                    @endforelse
                </div>
            </div>

            <!-- Featured Image -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-image text-rose-500 mr-2"></i>
                    Featured Image
                </h3>
                
                @if($post->featured_image)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                    <img src="{{ Storage::url($post->featured_image) }}" class="w-full h-40 object-cover rounded-lg">
                </div>
                @endif
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-rose-400 transition cursor-pointer" id="featuredDropzone" onclick="document.getElementById('featured_image').click()">
                    <input type="file" name="featured_image" id="featured_image" accept="image/*" class="hidden">
                    <div id="featuredPreviewContainer">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-600">{{ $post->featured_image ? 'Change image' : 'Click to upload' }}</p>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                    </div>
                </div>
                @error('featured_image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</form>

<!-- TinyMCE Script -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '.tinymce-editor',
        height: 500,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link image media | code fullscreen | help',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 1.6; }',
        menubar: true,
        branding: false,
        promotion: false,
        setup: function(editor) {
            editor.on('change', function() {
                editor.save();
            });
        }
    });

    // Featured Image Preview
    document.getElementById('featured_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('featuredPreviewContainer').innerHTML = `
                    <img src="${e.target.result}" class="w-full h-40 object-cover rounded-lg">
                    <p class="text-sm text-gray-600 mt-2">${file.name}</p>
                `;
            };
            reader.readAsDataURL(file);
        }
    });

    // Gallery Images Preview
    document.getElementById('gallery_images').addEventListener('change', function(e) {
        const preview = document.getElementById('galleryPreview');
        preview.innerHTML = '';
        
        Array.from(e.target.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center rounded-lg">
                        <span class="text-white text-xs">${file.name.substring(0, 15)}...</span>
                    </div>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endsection
