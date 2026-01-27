<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Post - VisionSphere</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tiny.cloud/1/a0gi9ib6oscgvosym1nvjux8wrne5tlwtqrltkwgxf9t8d2f/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        .mobile-menu-overlay {
            backdrop-filter: blur(4px);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen lg:flex">
        <!-- Mobile Menu Overlay -->
        <div id="mobile-menu-overlay" class="mobile-menu-overlay fixed inset-0 z-40 bg-black bg-opacity-50 hidden lg:hidden" onclick="toggleMobileMenu()"></div>

        <!-- Sidebar -->
        <div id="sidebar" class="sidebar-transition fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform -translate-x-full lg:translate-x-0 lg:transform-none lg:opacity-100 lg:static lg:z-auto overflow-y-auto">
            <div class="flex flex-col h-full">
            <!-- Logo/Brand -->
            <div class="flex items-center justify-between p-6 border-b border-rose-200">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo.svg?v=' . time()) }}" alt="VisionSphere Logo" class="w-14 h-14 rounded-lg shadow-sm" style="width: 56px; height: 56px;">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">VisionSphere</h2>
                        <p class="text-xs text-gray-500">For women, by women</p>
                    </div>
                </div>
                <button onclick="toggleMobileMenu()" aria-label="Toggle menu" aria-expanded="false" class="lg:hidden text-gray-500 hover:text-rose-600 focus:outline-none p-2 rounded-md focus:ring-2 focus:ring-rose-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('user.dashboard') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300">
                    <i class="fas fa-tachometer-alt mr-3 text-lg"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <!-- My Shop Section -->
                <div class="pt-4 pb-2">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">My Shop</span>
                </div>
                @php
                    $userShop = auth()->user()->shop;
                @endphp
                @if($userShop)
                    <a href="{{ route('user.shop.dashboard') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300">
                        <i class="fas fa-store mr-3 text-lg"></i>
                        <span class="font-medium">{{ Str::limit($userShop->name, 15) }}</span>
                        @if($userShop->subscription_status === 'trial')
                            <span class="ml-auto text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">Trial</span>
                        @elseif($userShop->subscription_status === 'active')
                            <span class="ml-auto text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">Pro</span>
                        @endif
                    </a>
                @else
                    <a href="{{ route('user.shop.create') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300">
                        <i class="fas fa-plus-circle mr-3 text-lg"></i>
                        <span class="font-medium">Create Shop</span>
                    </a>
                @endif

                <!-- Content Section -->
                <div class="pt-4 pb-2">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">Content</span>
                </div>
                <a href="{{ route('user.posts.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300">
                    <i class="fas fa-newspaper mr-3 text-lg"></i>
                    <span class="font-medium">My Posts</span>
                </a>
                <a href="{{ route('user.posts.create') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300">
                    <i class="fas fa-plus mr-3 text-lg"></i>
                    <span class="font-medium">Create Post</span>
                </a>

                <!-- Account Section -->
                <div class="pt-4 pb-2">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">Account</span>
                </div>
                <a href="{{ route('user.profile') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300">
                    <i class="fas fa-user mr-3 text-lg"></i>
                    <span class="font-medium">Profile</span>
                </a>
                <a href="{{ route('user.settings') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300">
                    <i class="fas fa-cog mr-3 text-lg"></i>
                    <span class="font-medium">Settings</span>
                </a>
            </nav>

            <!-- User Info -->
            <div class="p-4 border-t border-rose-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-rose-400 to-pink-400 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-gray-600 p-1">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-0">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button onclick="toggleMobileMenu()" aria-label="Toggle menu" class="lg:hidden text-gray-500 hover:text-rose-600 focus:outline-none p-2 rounded-md focus:ring-2 focus:ring-rose-300">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Edit Post</h1>
                            <p class="text-gray-600 text-sm">Update your post content</p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('user.posts.show', $post) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-eye mr-2"></i>View Post
                        </a>
                        <a href="{{ route('user.posts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Posts
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 lg:p-8">
                <form action="{{ route('user.posts.update', $post) }}" method="POST" enctype="multipart/form-data" id="postForm">
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
                                <textarea name="content" id="content" class="tinymce-editor">{{ old('content', $post->content) }}</textarea>
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

                                @if($post->gallery_images && count($post->gallery_images) > 0)
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-600 mb-2">Current Gallery Images:</p>
                                        <div class="grid grid-cols-4 gap-4" id="existingGallery">
                                            @foreach($post->gallery_images as $image)
                                                <div class="relative group">
                                                    <img src="{{ \Illuminate\Support\Str::startsWith($image, 'http') ? $image : \Illuminate\Support\Facades\Storage::url($image) }}" class="w-full h-24 object-cover rounded-lg">
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
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF, WEBP up to 10MB each</p>
                                    </div>
                                </div>
                                <div id="galleryPreview" class="grid grid-cols-4 gap-4 mt-4"></div>
                                @error('gallery_images.*')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
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

                                @if(auth()->user()->hasPermission('publish-posts'))
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
                                    <a href="{{ route('user.posts.show', $post) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                                        <i class="fas fa-eye mr-2"></i>Preview
                                    </a>
                                    <a href="{{ route('user.posts.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                        <i class="fas fa-times mr-2"></i>Cancel
                                    </a>
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
                                @php $selectedTags = old('tags', $post->tags->pluck('id')->toArray()); @endphp
                                <div class="space-y-2 max-h-48 overflow-y-auto">
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
                                        <img src="{{ \Illuminate\Support\Str::startsWith($post->featured_image, 'http') ? $post->featured_image : \Illuminate\Support\Facades\Storage::url($post->featured_image) }}" class="w-full h-40 object-cover rounded-lg">
                                    </div>
                                @endif

                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-rose-400 transition cursor-pointer" id="featuredDropzone" onclick="document.getElementById('featured_image').click()">
                                    <input type="file" name="featured_image" id="featured_image" accept="image/*" class="hidden">
                                    <div id="featuredPreviewContainer">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                        <p class="text-sm text-gray-600">{{ $post->featured_image ? 'Change image' : 'Click to upload' }}</p>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF, WEBP up to 10MB</p>
                                    </div>
                                </div>
                                @error('featured_image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Font Awesome (CSS) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" referrerpolicy="no-referrer">

    <script>
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');
            const buttons = document.querySelectorAll('button[onclick="toggleMobileMenu()"]');

            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                buttons.forEach(b => b.setAttribute('aria-expanded', 'true'));
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                buttons.forEach(b => b.setAttribute('aria-expanded', 'false'));
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.add('-translate-x-full');

            tinymce.init({
                selector: '.tinymce-editor',
                height: 500,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount', 'paste'
                ],
                toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link image media | code fullscreen | help',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 1.6; }',
                menubar: true,
                branding: false,
                promotion: false,
                paste_data_images: true,
                images_upload_handler: function (blobInfo, success, failure) {
                    let xhr, formData;

                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '{{ route('user.upload-image') }}');

                    xhr.onload = function() {
                        let json;

                        if (xhr.status != 200) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }

                        json = JSON.parse(xhr.responseText);

                        if (!json || typeof json.location != 'string') {
                            failure('Invalid JSON: ' + xhr.responseText);
                            return;
                        }

                        success(json.location);
                    };

                    formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                    xhr.send(formData);
                },
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save();
                    });
                }
            });

            const featuredInput = document.getElementById('featured_image');
            const galleryInput = document.getElementById('gallery_images');
            const galleryPreview = document.getElementById('galleryPreview');
            const galleryDropzone = document.getElementById('galleryDropzone');

            const renderGalleryPreviews = (files) => {
                if (!galleryPreview) return;
                galleryPreview.innerHTML = '';

                Array.from(files).forEach((file) => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                            <img src="${event.target.result}" class="w-full h-24 object-cover rounded-lg">
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center rounded-lg">
                                <span class="text-white text-xs">${file.name.substring(0, 15)}...</span>
                            </div>
                        `;
                        galleryPreview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            };

            if (featuredInput) {
                featuredInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const container = document.getElementById('featuredPreviewContainer');
                        if (!container) return;
                        container.innerHTML = `
                            <img src="${event.target.result}" class="w-full h-40 object-cover rounded-lg">
                            <p class="text-sm text-gray-600 mt-2">${file.name}</p>
                        `;
                    };
                    reader.readAsDataURL(file);
                });
            }

            if (galleryInput) {
                galleryInput.addEventListener('change', function(e) {
                    renderGalleryPreviews(e.target.files);
                });
            }

            const preventDefaults = (event) => {
                event.preventDefault();
                event.stopPropagation();
            };

            if (galleryDropzone) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach((eventName) => {
                    galleryDropzone.addEventListener(eventName, preventDefaults);
                });

                ['dragenter', 'dragover'].forEach((eventName) => {
                    galleryDropzone.addEventListener(eventName, () => galleryDropzone.classList.add('border-rose-400', 'bg-rose-50'));
                });

                ['dragleave', 'drop'].forEach((eventName) => {
                    galleryDropzone.addEventListener(eventName, () => galleryDropzone.classList.remove('border-rose-400', 'bg-rose-50'));
                });

                galleryDropzone.addEventListener('drop', function(e) {
                    const dt = e.dataTransfer;
                    const files = dt?.files;
                    if (files && galleryInput) {
                        galleryInput.files = files;
                        galleryInput.dispatchEvent(new Event('change'));
                    }
                });
            }
        });
    </script>
</body>
</html>
