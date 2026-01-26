<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - VisionSphere – Explore your world of ideas and stories.</title>
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
        <div id="sidebar" class="sidebar-transition fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform lg:transform-none lg:opacity-100 lg:static lg:z-auto overflow-y-auto">
            <div class="flex flex-col h-full">
            <!-- Logo/Brand -->
            <div class="flex items-center justify-between p-6 border-b border-rose-200">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo.svg?v=' . time()) }}" alt="VisionSphere Logo" class="w-14 h-14 rounded-lg shadow-sm" style="width: 56px; height: 56px;">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">VisionSphere – Explore your world of ideas and stories.</h2>
                        <p class="text-xs text-gray-500">For women, by women</p>
                    </div>
                </div>
                <button onclick="toggleMobileMenu()" aria-label="Toggle menu" aria-expanded="false" class="lg:hidden text-gray-500 hover:text-rose-600 focus:outline-none p-2 rounded-md focus:ring-2 focus:ring-rose-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('user.dashboard') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->routeIs('user.dashboard') ? 'bg-rose-100 text-rose-800 border-r-4 border-rose-500' : '' }}">
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
                    <a href="{{ route('user.shop.dashboard') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->routeIs('user.shop.*') ? 'bg-rose-100 text-rose-800 border-r-4 border-rose-500' : '' }}">
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
                <a href="{{ route('admin.posts.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->routeIs('admin.posts.index') ? 'bg-rose-100 text-rose-800 border-r-4 border-rose-500' : '' }}">
                    <i class="fas fa-newspaper mr-3 text-lg"></i>
                    <span class="font-medium">My Posts</span>
                </a>
                <a href="{{ route('admin.posts.create') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->routeIs('admin.posts.create') ? 'bg-rose-100 text-rose-800 border-r-4 border-rose-500' : '' }}">
                    <i class="fas fa-plus mr-3 text-lg"></i>
                    <span class="font-medium">Create Post</span>
                </a>
                
                <!-- Account Section -->
                <div class="pt-4 pb-2">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">Account</span>
                </div>
                <a href="{{ route('user.profile') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->routeIs('user.profile*') ? 'bg-rose-100 text-rose-800 border-r-4 border-rose-500' : '' }}">
                    <i class="fas fa-user mr-3 text-lg"></i>
                    <span class="font-medium">Profile</span>
                </a>
                <a href="{{ route('user.settings') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->routeIs('user.settings*') ? 'bg-rose-100 text-rose-800 border-r-4 border-rose-500' : '' }}">
                    <i class="fas fa-cog mr-3 text-lg"></i>
                    <span class="font-medium">Settings</span>
                </a>
            </nav>

            <!-- User Info -->
            <div class="p-4 border-t border-rose-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-rose-400 to-pink-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-rose-600">{{ auth()->user()->status == 1 ? 'Active Writer' : 'Pending Approval' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Main Content Area -->
        <div class="flex-1 min-h-screen">
        <!-- Top Header -->
        <header class="bg-white shadow-sm border-b border-rose-200 sticky top-0 z-30">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <button onclick="toggleMobileMenu()" aria-label="Open sidebar" aria-expanded="false" class="lg:hidden mr-4 text-gray-500 hover:text-rose-600 focus:outline-none p-2 rounded-md focus:ring-2 focus:ring-rose-300">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-2xl font-bold text-gray-900">My Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-400 hover:text-rose-600 focus:outline-none">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-0 right-0 block w-2 h-2 bg-rose-500 rounded-full"></span>
                        </button>
                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button onclick="toggleProfileMenu()" aria-haspopup="true" aria-expanded="false" class="flex items-center space-x-2 text-gray-700 hover:text-rose-700 focus:outline-none p-2 rounded-md focus:ring-2 focus:ring-rose-300">
                                <div class="w-8 h-8 bg-gradient-to-r from-rose-400 to-pink-400 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <span class="hidden md:block text-sm font-medium">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div id="profile-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 hidden">
                                <div class="py-1">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-rose-50">Profile Settings</a>
                                    <form method="POST" action="{{ route('admin.logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-rose-50">Logout</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="p-4 sm:p-6 lg:p-8">
            <!-- Welcome Section -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome back, {{ auth()->user()->name }}!</h2>
                <p class="text-gray-600">Track your writing progress and manage your blog posts.</p>
            </div>

            <!-- Account Status Banner -->
            @if(auth()->user()->status != 1)
            <div class="mb-8 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Your account is pending admin approval. You'll be able to publish posts once approved.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Posts</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_posts'] }}</p>
                            <p class="text-xs text-green-600 mt-1">
                                <i class="fas fa-arrow-up mr-1"></i>All your content
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-rose-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-newspaper text-rose-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Published</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['published_posts'] }}</p>
                            <p class="text-xs text-green-600 mt-1">
                                <i class="fas fa-check-circle mr-1"></i>Live on blog
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-pink-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-pink-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Drafts</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['draft_posts'] }}</p>
                            <p class="text-xs text-yellow-600 mt-1">
                                <i class="fas fa-edit mr-1"></i>Work in progress
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Account Status</p>
                            <p class="text-3xl font-bold text-gray-900">{{ auth()->user()->status == 1 ? 'Active' : 'Pending' }}</p>
                            <p class="text-xs {{ auth()->user()->status == 1 ? 'text-green-600' : 'text-yellow-600' }} mt-1">
                                <i class="fas fa-{{ auth()->user()->status == 1 ? 'check-circle' : 'clock' }} mr-1"></i>{{ auth()->user()->status == 1 ? 'Verified' : 'Awaiting approval' }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-rose-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user text-rose-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Posts & Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Posts -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">My Recent Posts</h3>
                        <a href="{{ route('admin.posts.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">View all</a>
                    </div>
                    @if($recentPosts->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentPosts as $post)
                            <div class="flex items-start space-x-4 p-4 rounded-lg hover:bg-gray-50 transition-colors duration-200 border border-gray-100">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-file-alt text-white text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-base font-semibold text-gray-900 truncate">{{ $post->title }}</h4>
                                            <div class="flex items-center space-x-4 mt-1">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    <i class="fas fa-{{ $post->status === 'published' ? 'check-circle' : 'edit' }} mr-1"></i>
                                                    {{ ucfirst($post->status) }}
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    <i class="fas fa-folder mr-1"></i>{{ $post->category->name ?? 'Uncategorized' }}
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    <i class="fas fa-calendar mr-1"></i>{{ $post->created_at->format('M d, Y') }}
                                                </span>
                                            </div>
                                            @if($post->excerpt)
                                            <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ Str::limit($post->excerpt, 120) }}</p>
                                            @endif
                                        </div>
                                        <div class="flex space-x-2 ml-4">
                                            <a href="{{ route('admin.posts.show', $post) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                            <a href="{{ route('admin.posts.edit', $post) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-pink-700 bg-pink-100 rounded-lg hover:bg-pink-200 transition-colors duration-200">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-6 text-center">
                            <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                <i class="fas fa-list mr-2"></i>View All Posts
                            </a>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-newspaper text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No posts yet</h3>
                            <p class="text-gray-500 mb-6">Start your writing journey by creating your first post</p>
                            <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center px-6 py-3 bg-rose-600 text-white text-sm font-medium rounded-lg hover:bg-rose-700 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>Create Your First Post
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions & Writing Tips -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-rose-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.posts.create') }}" class="flex items-center w-full px-4 py-3 min-h-[48px] bg-rose-50 text-rose-700 rounded-lg hover:bg-rose-100 transition-colors duration-200 group">
                                <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-rose-200 transition-colors duration-200">
                                    <i class="fas fa-plus text-rose-600"></i>
                                </div>
                                <div>
                                    <span class="font-medium">Write New Post</span>
                                    <p class="text-xs text-rose-600">Start creating content</p>
                                </div>
                            </a>
                            <a href="{{ route('admin.posts.index') }}" class="flex items-center w-full px-4 py-3 min-h-[48px] bg-pink-50 text-pink-700 rounded-lg hover:bg-pink-100 transition-colors duration-200 group">
                                <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-pink-200 transition-colors duration-200">
                                    <i class="fas fa-list text-pink-600"></i>
                                </div>
                                <div>
                                    <span class="font-medium">Manage Posts</span>
                                    <p class="text-xs text-pink-600">Edit and organize</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Writing Tips -->
                    <div class="bg-gradient-to-br from-rose-50 to-pink-50 rounded-xl shadow-sm border border-rose-200 p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-rose-500 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-lightbulb text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Writing Tips</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-rose-500 mt-0.5 text-sm"></i>
                                <p class="text-sm text-gray-600">Write compelling headlines that grab attention</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-rose-500 mt-0.5 text-sm"></i>
                                <p class="text-sm text-gray-600">Use categories to organize your content</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-rose-500 mt-0.5 text-sm"></i>
                                <p class="text-sm text-gray-600">Save drafts and publish when ready</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
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

        function toggleProfileMenu() {
            const menu = document.getElementById('profile-menu');
            menu.classList.toggle('hidden');
        }

        // Close profile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('profile-menu');
            const button = event.target.closest('button[onclick="toggleProfileMenu()"]');

            if (!button && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        // Initialize mobile sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.add('-translate-x-full', 'lg:translate-x-0');
        });
    </script>
</body>
</html>