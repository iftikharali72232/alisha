<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'All Shops') - {{ config('app.name') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .shop-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ url('/') }}" class="text-2xl font-bold text-pink-600">
                    {{ config('app.name') }}
                </a>
                <nav class="flex items-center space-x-4">
                    <a href="{{ url('/shops') }}" class="text-gray-700 hover:text-pink-600">All Shops</a>
                    @auth
                        <a href="{{ route('user.dashboard') }}" class="text-gray-700 hover:text-pink-600">Dashboard</a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-pink-500 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Discover Amazing Shops</h1>
            <p class="text-xl text-pink-100 mb-8">Browse our collection of unique online stores</p>
            
            <!-- Search -->
            <form action="{{ url('/shops') }}" method="GET" class="max-w-2xl mx-auto">
                <div class="flex">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Search shops..." 
                        class="flex-1 px-6 py-3 rounded-l-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300">
                    <button type="submit" class="px-6 py-3 bg-pink-700 hover:bg-pink-800 rounded-r-lg">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Shops Grid -->
    <main class="max-w-7xl mx-auto px-4 py-12">
        @if($shops->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($shops as $shop)
                    <a href="{{ route('shop.show', $shop->slug) }}" class="shop-card bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300">
                        <!-- Shop Banner/Logo -->
                        <div class="h-32 bg-gradient-to-r from-pink-400 to-purple-500 relative">
                            @if($shop->banner)
                                <img src="{{ Storage::url($shop->banner) }}" alt="{{ $shop->name }}" class="w-full h-full object-cover">
                            @endif
                            <div class="absolute -bottom-8 left-6">
                                @if($shop->logo)
                                    <img src="{{ Storage::url($shop->logo) }}" alt="{{ $shop->name }}" class="w-16 h-16 rounded-full border-4 border-white object-cover shadow-lg">
                                @else
                                    <div class="w-16 h-16 rounded-full border-4 border-white bg-white flex items-center justify-center shadow-lg">
                                        <span class="text-2xl font-bold text-pink-500">{{ substr($shop->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="pt-10 px-6 pb-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-1">{{ $shop->name }}</h2>
                            <p class="text-gray-500 text-sm mb-3">{{ Str::limit($shop->tagline ?? $shop->description, 60) }}</p>
                            
                            <!-- Stats -->
                            <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                                <span><i class="fas fa-box mr-1"></i> {{ $shop->products_count ?? $shop->products->count() }} Products</span>
                                @if($shop->reviews_avg_rating)
                                    <span><i class="fas fa-star text-yellow-400 mr-1"></i> {{ number_format($shop->reviews_avg_rating, 1) }}</span>
                                @endif
                            </div>

                            <!-- Categories -->
                            @if($shop->categories->count() > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($shop->categories->take(3) as $category)
                                        <span class="px-2 py-1 bg-pink-100 text-pink-600 text-xs rounded-full">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                    @if($shop->categories->count() > 3)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                            +{{ $shop->categories->count() - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $shops->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                    <i class="fas fa-store text-4xl text-gray-400"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">No Shops Found</h2>
                <p class="text-gray-500">Try adjusting your search or check back later</p>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p class="text-sm mt-2">
                <a href="#" class="hover:text-white">Privacy Policy</a> • 
                <a href="#" class="hover:text-white">Terms of Service</a>
            </p>
        </div>
    </footer>
</body>
</html>
