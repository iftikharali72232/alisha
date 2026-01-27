<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} - VisionSphere</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                            <h1 class="text-2xl font-bold text-gray-900">{{ $post->title }}</h1>
                            <p class="text-gray-600 text-sm">View your post</p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('user.posts.edit', $post) }}" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white text-sm font-medium rounded-lg hover:bg-rose-700 transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>Edit Post
                        </a>
                        <a href="{{ route('user.posts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Posts
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 lg:p-8">
                <div class="max-w-4xl mx-auto">
                    <!-- Post Meta -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    <i class="fas fa-{{ $post->status === 'published' ? 'check-circle' : 'edit' }} mr-1"></i>
                                    {{ ucfirst($post->status) }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-folder mr-1"></i>{{ $post->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>{{ $post->created_at->format('M d, Y \a\t g:i A') }}
                            </span>
                        </div>

                        @if($post->tags->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($post->tags as $tag)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-tag mr-1"></i>{{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Featured Image -->
                    @if($post->featured_image)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-64 object-cover rounded-lg">
                        </div>
                    @endif

                    <!-- Post Content -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="prose prose-lg max-w-none">
                            {!! $post->content !!}
                        </div>
                    </div>

                    <!-- Gallery Images -->
                    @if($post->gallery_images && count($post->gallery_images) > 0)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Gallery</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($post->gallery_images as $image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="Gallery image" class="w-full h-32 object-cover rounded-lg">
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');

            if (sidebar.classList.contains('transform')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
    </script>
</body>
</html>