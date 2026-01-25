<!-- Sidebar -->
<div id="sidebar" class="sidebar-transition fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform lg:transform-none lg:opacity-100 lg:static lg:z-auto overflow-y-auto">
    <div class="flex flex-col h-full">
        <!-- Logo/Brand -->
        <div class="flex items-center justify-between p-6 border-b border-rose-200">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/logo.png') }}" alt="VisionSphere Logo" class="w-14 h-14 rounded-lg object-cover shadow-sm">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">VisionSphere Admin</h2>
                    <p class="text-xs text-gray-500">Management Panel</p>
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
            <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/dashboard') ? 'bg-rose-100 text-rose-800 border-r-4 border-rose-500' : '' }}">
                <i class="fas fa-tachometer-alt mr-3 text-lg"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Content -->
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">Content</p>
            <a href="{{ route('admin.posts.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/posts*') && !request()->is('admin/posts/create') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-newspaper mr-3 text-lg"></i>
                <span class="font-medium">All Posts</span>
            </a>
            <a href="{{ route('admin.posts.create') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/posts/create') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-plus mr-3 text-lg"></i>
                <span class="font-medium">Create Post</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/categories*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-folder mr-3 text-lg"></i>
                <span class="font-medium">Categories</span>
            </a>
            <a href="{{ route('admin.tags.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/tags*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-tags mr-3 text-lg"></i>
                <span class="font-medium">Tags</span>
            </a>
            <a href="{{ route('admin.comments.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/comments*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-comments mr-3 text-lg"></i>
                <span class="font-medium">Comments</span>
            </a>

            <!-- Media -->
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">Media</p>
            <a href="{{ route('admin.sliders.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/sliders*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-images mr-3 text-lg"></i>
                <span class="font-medium">Sliders</span>
            </a>
            <a href="{{ route('admin.galleries.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/galleries*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-photo-video mr-3 text-lg"></i>
                <span class="font-medium">Gallery</span>
            </a>

            <!-- Pages -->
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">Pages</p>
            <a href="{{ route('admin.pages.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/pages*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-file-alt mr-3 text-lg"></i>
                <span class="font-medium">Static Pages</span>
            </a>

            <!-- System -->
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">System</p>
            @if(auth()->user()->is_admin)
            <a href="{{ route('admin.users.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/users*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-users mr-3 text-lg"></i>
                <span class="font-medium">User Management</span>
            </a>
            @endif
            <a href="{{ route('admin.settings') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/settings*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-cog mr-3 text-lg"></i>
                <span class="font-medium">Settings</span>
            </a>
            <a href="{{ route('admin.profile') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->is('admin/profile*') ? 'bg-rose-100 text-rose-800' : '' }}">
                <i class="fas fa-user-circle mr-3 text-lg"></i>
                <span class="font-medium">Profile</span>
            </a>
        </nav>

        <!-- User Info -->
        <div class="p-4 border-t border-rose-200">
            <div class="flex items-center space-x-3">
                @if(auth()->user()->avatar)
                <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover">
                @else
                <div class="w-10 h-10 bg-gradient-to-r from-rose-400 to-pink-400 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-rose-600">{{ auth()->user()->is_admin ? 'Administrator' : 'Author' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>