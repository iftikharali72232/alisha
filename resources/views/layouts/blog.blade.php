<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $siteName = \App\Models\Setting::get('site_name', 'Vision Sphere');
        $siteFavicon = \App\Models\Setting::get('site_favicon');
    @endphp
    <title>@yield('title', 'Home') | {{ $siteName }}</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('site_description', 'Explore your world of ideas and stories'))">
    
    <!-- Favicon -->
    @if($siteFavicon)
        <link rel="icon" type="image/png" href="{{ Storage::url($siteFavicon) }}">
    @else
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    @endif
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        @php $siteLogo = \App\Models\Setting::get('site_logo'); @endphp
                        @if($siteLogo)
                            <img src="{{ Storage::url($siteLogo) }}" alt="{{ $siteName }}" class="h-10 w-auto" style="max-width: none; height: 40px; width: auto;">
                        @else
                            <img src="{{ asset('images/logo.svg?v=' . time()) }}" alt="{{ $siteName }}" class="h-10 w-auto" style="max-width: none; height: 40px; width: auto;">
                        @endif
                        <span class="text-xl font-bold text-gray-900 hidden sm:inline">{{ $siteName }}</span>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-rose-600 transition {{ request()->routeIs('home') ? 'text-rose-600 font-medium' : '' }}">Home</a>
                    <a href="{{ route('blog.index') }}" class="text-gray-700 hover:text-rose-600 transition {{ request()->routeIs('blog.index') ? 'text-rose-600 font-medium' : '' }}">Blog</a>
                    <a href="{{ route('blog.gallery') }}" class="text-gray-700 hover:text-rose-600 transition {{ request()->routeIs('blog.gallery') ? 'text-rose-600 font-medium' : '' }}">Gallery</a>
                    <a href="{{ route('blog.about') }}" class="text-gray-700 hover:text-rose-600 transition {{ request()->routeIs('blog.about') ? 'text-rose-600 font-medium' : '' }}">About</a>
                    <a href="{{ route('blog.contact') }}" class="text-gray-700 hover:text-rose-600 transition {{ request()->routeIs('blog.contact') ? 'text-rose-600 font-medium' : '' }}">Contact</a>
                </div>
                
                <!-- Search & Auth -->
                <div class="flex items-center space-x-4">
                    <form action="{{ route('blog.search') }}" method="GET" class="hidden sm:block">
                        <div class="relative">
                            <input type="text" name="q" placeholder="Search..." class="w-48 pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent text-sm">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </form>
                    
                    @auth
                        <a href="{{ auth()->user()->canAccessAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="text-gray-700 hover:text-rose-600 transition">
                            <i class="fas fa-user-circle text-xl"></i>
                        </a>
                    @else
                        <a href="{{ route('admin.login') }}" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white rounded-full text-sm hover:bg-rose-700 transition">
                            Login
                        </a>
                    @endauth
                    
                    <!-- Mobile menu button -->
                    <button onclick="toggleMobileNav()" class="md:hidden text-gray-700">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobile-nav" class="md:hidden hidden pb-4">
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg text-gray-700 hover:bg-rose-50 hover:text-rose-600">Home</a>
                    <a href="{{ route('blog.index') }}" class="px-3 py-2 rounded-lg text-gray-700 hover:bg-rose-50 hover:text-rose-600">Blog</a>
                    <a href="{{ route('blog.gallery') }}" class="px-3 py-2 rounded-lg text-gray-700 hover:bg-rose-50 hover:text-rose-600">Gallery</a>
                    <a href="{{ route('blog.about') }}" class="px-3 py-2 rounded-lg text-gray-700 hover:bg-rose-50 hover:text-rose-600">About</a>
                    <a href="{{ route('blog.contact') }}" class="px-3 py-2 rounded-lg text-gray-700 hover:bg-rose-50 hover:text-rose-600">Contact</a>
                    <form action="{{ route('blog.search') }}" method="GET" class="mt-2">
                        <input type="text" name="q" placeholder="Search..." class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-rose-500">
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <img src="{{ asset('images/logo.svg?v=' . time()) }}" alt="Logo" class="h-10 w-10 rounded-lg" style="width: 40px; height: 40px;">
                        <span class="text-xl font-bold text-white">VisionSphere – Explore your world of ideas and stories.</span>
                    </div>
                    <p class="text-gray-400 mb-4">{{ \App\Models\Setting::get('site_description', 'A beautiful blog sharing insights and stories.') }}</p>
                    <div class="flex space-x-4">
                        @if(\App\Models\Setting::get('facebook_url'))
                        <a href="{{ \App\Models\Setting::get('facebook_url') }}" target="_blank" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        @endif
                        @if(\App\Models\Setting::get('twitter_url'))
                        <a href="{{ \App\Models\Setting::get('twitter_url') }}" target="_blank" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        @endif
                        @if(\App\Models\Setting::get('instagram_url'))
                        <a href="{{ \App\Models\Setting::get('instagram_url') }}" target="_blank" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        @endif
                        @if(\App\Models\Setting::get('youtube_url'))
                        <a href="{{ \App\Models\Setting::get('youtube_url') }}" target="_blank" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-youtube text-xl"></i>
                        </a>
                        @endif
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition">Home</a></li>
                        <li><a href="{{ route('blog.index') }}" class="text-gray-400 hover:text-white transition">Blog</a></li>
                        <li><a href="{{ route('blog.about') }}" class="text-gray-400 hover:text-white transition">About</a></li>
                        <li><a href="{{ route('blog.contact') }}" class="text-gray-400 hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                
                <!-- Legal -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('blog.page', 'privacy-policy') }}" class="text-gray-400 hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="{{ route('blog.page', 'terms-of-service') }}" class="text-gray-400 hover:text-white transition">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">{{ \App\Models\Setting::get('footer_text', '© ' . date('Y') . ' VisionSphere – Explore your world of ideas and stories. All rights reserved.') }}</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileNav() {
            const nav = document.getElementById('mobile-nav');
            nav.classList.toggle('hidden');
        }
    </script>
    @yield('scripts')
</body>
</html>
