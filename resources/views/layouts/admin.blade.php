<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $siteName = \App\Models\Setting::get('site_name', 'Vision Sphere');
        $siteFavicon = \App\Models\Setting::get('site_favicon');
    @endphp
    <title>@yield('title', 'Admin Dashboard') | {{ $siteName }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    @if($siteFavicon)
        <link rel="icon" type="image/png" href="{{ Storage::url($siteFavicon) }}">
    @else
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    @endif
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-transition { transition: transform 0.3s ease-in-out; }
        /* Print rules: hide sidebar and UI chrome when printing */
        @media print {
            #sidebar, .sidebar-transition, .mobile-menu-overlay, .no-print, .wa-widget {
                display: none !important;
                visibility: hidden !important;
            }
            body { background: #fff !important; }
        }
        .mobile-menu-overlay { backdrop-filter: blur(4px); }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu-overlay" class="mobile-menu-overlay fixed inset-0 z-40 bg-black bg-opacity-50 hidden lg:hidden" onclick="toggleMobileMenu()"></div>

    <div class="min-h-screen lg:flex">
        @include('admin.partials.sidebar')

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
                        <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
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
                                    <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-rose-50">Profile Settings</a>
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

            <main class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Font Awesome (CSS) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" referrerpolicy="no-referrer">

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/a0gi9ib6oscgvosym1nvjux8wrne5tlwtqrltkwgxf9t8d2f/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    @yield('scripts')

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

            if (!button && menu && !menu.contains(event.target)) {
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