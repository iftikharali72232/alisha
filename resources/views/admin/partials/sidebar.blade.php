<!-- Sidebar -->
@php
    $user = auth()->user();
    $hasPermission = fn($permission) => $user->isSuperAdmin() || $user->hasPermission($permission);
    $hasAnyPermission = fn($permissions) => $user->isSuperAdmin() || $user->hasAnyPermission($permissions);
    $siteLogo = \App\Models\Setting::get('site_logo');
    $siteName = \App\Models\Setting::get('site_name', 'Vision Sphere');
@endphp
<div id="sidebar" class="sidebar-transition fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform lg:transform-none lg:opacity-100 lg:static lg:z-auto overflow-y-auto">
    <div class="flex flex-col h-full">
        <!-- Logo/Brand -->
        <div class="flex items-center justify-between p-6 border-b border-rose-200">
            <div class="flex items-center space-x-4">
                @if($siteLogo)
                    <img src="{{ Storage::url($siteLogo) }}" alt="{{ $siteName }} Logo" class="w-14 h-14 rounded-lg object-cover shadow-sm">
                @else
                    <img src="{{ asset('images/logo.svg?v=' . time()) }}" alt="{{ $siteName }} Logo" class="w-14 h-14 rounded-lg shadow-sm" style="width: 56px; height: 56px;">
                @endif
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $siteName }}</h2>
                    <p class="text-xs text-gray-500">Admin Panel</p>
                </div>
            </div>
            <button onclick="toggleMobileMenu()" aria-label="Toggle menu" aria-expanded="false" class="lg:hidden text-gray-500 hover:text-rose-600 focus:outline-none p-2 rounded-md focus:ring-2 focus:ring-rose-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <!-- Main -->
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Main</p>
            @if($hasPermission('view-dashboard'))
            <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/dashboard') ? 'bg-rose-100 text-rose-800 border-r-4 border-rose-500' : '' }}">
                <i class="fas fa-tachometer-alt mr-3 text-lg"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            @endif

            <!-- Content -->
            @if($hasAnyPermission(['view-posts', 'create-posts', 'view-categories', 'view-tags', 'view-comments']))
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">Content</p>
            @endif
            
            @if($hasPermission('view-posts'))
            <a href="{{ route('admin.posts.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/posts*') && !request()->is('admin/posts/create') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-newspaper mr-3 text-lg"></i>
                <span class="font-medium">All Posts</span>
            </a>
            @endif
            
            @if($hasPermission('create-posts'))
            <a href="{{ route('admin.posts.create') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/posts/create') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-plus mr-3 text-lg"></i>
                <span class="font-medium">Create Post</span>
            </a>
            @endif
            
            @if($hasPermission('view-categories'))
            <a href="{{ route('admin.categories.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/categories*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-folder mr-3 text-lg"></i>
                <span class="font-medium">Categories</span>
            </a>
            @endif
            
            @if($hasPermission('view-tags'))
            <a href="{{ route('admin.tags.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/tags*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-tags mr-3 text-lg"></i>
                <span class="font-medium">Tags</span>
            </a>
            @endif
            
            @if($hasPermission('view-comments'))
            <a href="{{ route('admin.comments.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/comments*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-comments mr-3 text-lg"></i>
                <span class="font-medium">Comments</span>
            </a>
            @endif

            <!-- Media -->
            @if($hasAnyPermission(['view-sliders', 'view-galleries']))
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">Media</p>
            @endif
            
            @if($hasPermission('view-sliders'))
            <a href="{{ route('admin.sliders.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/sliders*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-images mr-3 text-lg"></i>
                <span class="font-medium">Sliders</span>
            </a>
            @endif
            
            @if($hasPermission('view-galleries'))
            <a href="{{ route('admin.galleries.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/galleries*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-photo-video mr-3 text-lg"></i>
                <span class="font-medium">Gallery</span>
            </a>
            @endif

            <!-- Pages -->
            @if($hasPermission('view-pages'))
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">Pages</p>
            <a href="{{ route('admin.pages.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/pages*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-file-alt mr-3 text-lg"></i>
                <span class="font-medium">Static Pages</span>
            </a>
            @endif

            <!-- Shops -->
            @if($user->isSuperAdmin())
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">Shops</p>
            <a href="{{ route('admin.shops.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/shops') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-store mr-3 text-lg"></i>
                <span class="font-medium">All Shops</span>
            </a>
            <a href="{{ route('admin.shop-plans.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/shop-plans*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-credit-card mr-3 text-lg"></i>
                <span class="font-medium">Subscription Plans</span>
            </a>
            <a href="{{ route('admin.shop-settings.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/shop-settings*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-sliders-h mr-3 text-lg"></i>
                <span class="font-medium">Shop Settings</span>
            </a>
            @endif

            <!-- System -->
            @if($hasAnyPermission(['view-users', 'view-roles', 'view-settings']))
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">System</p>
            @endif
            
            @if($hasPermission('view-users'))
            <a href="{{ route('admin.users.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/users*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-users mr-3 text-lg"></i>
                <span class="font-medium">Users</span>
            </a>
            @endif
            
            @if($hasPermission('view-roles'))
            <a href="{{ route('admin.roles.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/roles*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-user-shield mr-3 text-lg"></i>
                <span class="font-medium">Roles</span>
            </a>
            @endif
            
            @if($hasPermission('view-settings'))
            <a href="{{ route('admin.settings') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/settings*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-cog mr-3 text-lg"></i>
                <span class="font-medium">Settings</span>
            </a>
            @endif
            
            <a href="{{ route('admin.profile') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/profile*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-user-circle mr-3 text-lg"></i>
                <span class="font-medium">Profile</span>
            </a>
        </nav>

        <!-- User Info -->
        <div class="p-4 border-t border-rose-200">
            <div class="flex items-center space-x-3">
                @if(auth()->user()->avatar)
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover">
                @else
                <div class="w-10 h-10 bg-gradient-to-r from-rose-400 to-pink-400 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-rose-600">{{ auth()->user()->role?->name ?? (auth()->user()->is_admin ? 'Administrator' : 'User') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>