<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shop Management') - {{ config('app.name') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .sidebar-link.active {
            background: linear-gradient(135deg, #ec4899, #f472b6);
            color: white;
        }
        .sidebar-link:hover:not(.active) {
            background-color: rgba(236, 72, 153, 0.1);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 h-full bg-white shadow-lg">
            <div class="h-full flex flex-col">
                <!-- Shop Info -->
                <div class="p-4 border-b bg-gradient-to-r from-pink-500 to-pink-600">
                    <div class="flex items-center space-x-3">
                        @if($shop->logo)
                            <img src="{{ Storage::url($shop->logo) }}" alt="{{ $shop->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-white">
                        @else
                            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center">
                                <span class="text-pink-600 font-bold text-lg">{{ substr($shop->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h2 class="text-white font-semibold truncate">{{ $shop->name }}</h2>
                            @if($shop->activeSubscription)
                                <span class="text-xs px-2 py-0.5 rounded-full 
                                    {{ $shop->activeSubscription->isOnTrial() ? 'bg-blue-200 text-blue-800' : 'bg-green-200 text-green-800' }}">
                                    {{ $shop->activeSubscription->isOnTrial() ? 'Trial' : $shop->activeSubscription->plan->name }}
                                </span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-full bg-red-200 text-red-800">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto p-4">
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('user.shop.dashboard') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('user.shop.dashboard') ? 'active' : 'text-gray-700' }}">
                                <i class="fas fa-tachometer-alt w-5"></i>
                                <span class="ml-3">Dashboard</span>
                            </a>
                        </li>
                        
                        <li class="pt-4">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">Catalog</span>
                        </li>
                        <li>
                            <a href="{{ route('user.shop.products.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('user.shop.products.*') ? 'active' : 'text-gray-700' }}">
                                <i class="fas fa-box w-5"></i>
                                <span class="ml-3">Products</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.shop.categories.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('user.shop.categories.*') ? 'active' : 'text-gray-700' }}">
                                <i class="fas fa-folder w-5"></i>
                                <span class="ml-3">Categories</span>
                            </a>
                        </li>

                        <li class="pt-4">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">Sales</span>
                        </li>
                        <li>
                            <a href="{{ route('user.shop.orders.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('user.shop.orders.*') ? 'active' : 'text-gray-700' }}">
                                <i class="fas fa-shopping-cart w-5"></i>
                                <span class="ml-3">Orders</span>
                                @php $pendingOrders = $shop->orders()->where('status', 'pending')->count(); @endphp
                                @if($pendingOrders > 0)
                                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingOrders }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.shop.customers.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('user.shop.customers.*') ? 'active' : 'text-gray-700' }}">
                                <i class="fas fa-users w-5"></i>
                                <span class="ml-3">Customers</span>
                            </a>
                        </li>

                        <li class="pt-4">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">Marketing</span>
                        </li>
                        <li>
                            <a href="{{ route('user.shop.offers.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('user.shop.offers.*') ? 'active' : 'text-gray-700' }}">
                                <i class="fas fa-tags w-5"></i>
                                <span class="ml-3">Offers</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.shop.coupons.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('user.shop.coupons.*') ? 'active' : 'text-gray-700' }}">
                                <i class="fas fa-ticket-alt w-5"></i>
                                <span class="ml-3">Coupons</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.shop.loyalty.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('user.shop.loyalty*') ? 'active' : 'text-gray-700' }}">
                                <i class="fas fa-star w-5"></i>
                                <span class="ml-3">Loyalty Points</span>
                                @if(!($shop->activeSubscription?->plan?->loyalty_enabled ?? false))
                                    <span class="ml-auto text-xs bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded">PRO</span>
                                @endif
                            </a>
                        </li>

                        <li class="pt-4">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">Store</span>
                        </li>
                        <li>
                            <a href="{{ route('user.shop.sliders.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('user.shop.sliders*') ? 'active' : 'text-gray-700' }}">
                                <i class="fas fa-images w-5"></i>
                                <span class="ml-3">Sliders</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.shop.reviews.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('user.shop.reviews*') ? 'active' : 'text-gray-700' }}">
                                <i class="fas fa-star-half-alt w-5"></i>
                                <span class="ml-3">Reviews</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.shop.settings.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('user.shop.settings*') ? 'active' : 'text-gray-700' }}">
                                <i class="fas fa-cog w-5"></i>
                                <span class="ml-3">Settings</span>
                            </a>
                        </li>
                        
                        <li class="pt-4">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">Account</span>
                        </li>
                        <li>
                            <a href="{{ route('user.shop.subscription') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('user.shop.subscription*') ? 'active' : 'text-gray-700' }}">
                                <i class="fas fa-crown w-5"></i>
                                <span class="ml-3">Subscription</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.dashboard') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg text-gray-700 transition">
                                <i class="fas fa-arrow-left w-5"></i>
                                <span class="ml-3">Back to Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- User Info -->
                <div class="p-4 border-t">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center">
                            <span class="text-pink-600 font-medium text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-700 truncate">{{ auth()->user()->name }}</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-500" title="Logout">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm sticky top-0 z-30">
                <div class="flex items-center justify-between px-4 py-3">
                    
                    <div class="flex-1 px-4">
                        <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Shop Management')</h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <a href="{{ route('shop.show', $shop->slug) }}" target="_blank" class="text-gray-600 hover:text-pink-600" title="View Shop">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        <a href="{{ route('user.shop.settings.index') }}" class="text-gray-600 hover:text-pink-600" title="Settings">
                            <i class="fas fa-cog"></i>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-4 md:p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span class="font-medium">Please fix the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('shop-content')
            </div>
        </main>
    </div>

    <script>
        // Sidebar is now always visible
        function toggleSidebar() {
            // No longer needed since sidebar is always visible
        }

        // Keep sidebar state sane on resize - no longer needed
        window.addEventListener('resize', () => {
            // No longer needed
        });
    </script>

    @stack('scripts')
</body>
</html>
