<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Dashboard') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        .mobile-menu-overlay {
            backdrop-filter: blur(4px);
        }
        /* Print rules: hide sidebars and UI chrome when printing */
        @media print {
            #sidebar, .sidebar-transition, .mobile-menu-overlay, .nav-link, .no-print, .wa-widget {
                display: none !important;
                visibility: hidden !important;
            }
            /* Ensure content area spans full width when printing */
            .min-h-screen {
                min-height: auto !important;
            }
            body {
                background: #fff !important;
            }
        }
    </style>
    @yield('styles')
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
                <a href="{{ route('user.posts.index') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->routeIs('user.posts.*') ? 'bg-rose-100 text-rose-800 border-r-4 border-rose-500' : '' }}">
                    <i class="fas fa-newspaper mr-3 text-lg"></i>
                    <span class="font-medium">My Posts</span>
                </a>
                <a href="{{ route('user.posts.create') }}" class="nav-link flex items-center px-4 py-3 min-h-[44px] text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-rose-300 {{ request()->routeIs('user.posts.create') ? 'bg-rose-100 text-rose-800 border-r-4 border-rose-500' : '' }}">
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
                        <span class="text-white font-bold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="inline">
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
            <header class="bg-white shadow-sm lg:hidden">
                <div class="flex items-center justify-between px-4 py-3">
                    <button onclick="toggleMobileMenu()" aria-label="Open menu" class="text-gray-500 hover:text-rose-600 focus:outline-none p-2 rounded-md focus:ring-2 focus:ring-rose-300">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-lg font-semibold text-gray-900">Dashboard</h1>
                    <div class="w-8"></div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');
            const isOpen = !sidebar.classList.contains('-translate-x-full');

            if (isOpen) {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            } else {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            }
        }
    </script>
    @yield('scripts')
</body>
</html>