<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $shop->name) - {{ config('app.name') }}</title>
    <meta name="description" content="{{ $shop->meta_description ?? $shop->description }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --shop-primary: {{ $shop->primary_color ?? '#ec4899' }};
            --shop-secondary: {{ $shop->secondary_color ?? '#8b5cf6' }};
        }
        .btn-primary {
            background-color: var(--shop-primary);
        }
        .btn-primary:hover {
            filter: brightness(90%);
        }
        .text-primary {
            color: var(--shop-primary);
        }
        .bg-primary {
            background-color: var(--shop-primary);
        }
        .border-primary {
            border-color: var(--shop-primary);
        }
        .product-card:hover {
            transform: translateY(-4px);
        }
        .offer-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('shop.show', $shop->slug) }}" class="flex items-center space-x-3">
                    @if($shop->logo)
                        <img src="{{ Storage::url($shop->logo) }}" alt="{{ $shop->name }}" class="h-10 w-10 rounded-full object-cover">
                    @endif
                    <span class="text-xl font-bold text-gray-800">{{ $shop->name }}</span>
                </a>

                <!-- Search -->
                <div class="hidden md:block flex-1 max-w-md mx-8">
                    <form action="{{ route('shop.search', $shop->slug) }}" method="GET">
                        <div class="relative">
                            <input type="text" name="q" placeholder="Search products..." 
                                class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </form>
                </div>

                <!-- Nav -->
                <nav class="flex items-center space-x-4">
                    <a href="{{ route('shop.cart', $shop->slug) }}" class="relative text-gray-700 hover:text-pink-600">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span id="cart-count" class="absolute -top-2 -right-2 bg-pink-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                            {{ session('cart_' . $shop->id) ? count(session('cart_' . $shop->id)) : 0 }}
                        </span>
                    </a>
                    
                    @if(session('shop_customer_' . $shop->id))
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-gray-700 hover:text-pink-600">
                                <i class="fas fa-user-circle text-xl"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2">
                                <a href="{{ route('shop.account', $shop->slug) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Account</a>
                                <a href="{{ route('shop.orders', $shop->slug) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Orders</a>
                                <form action="{{ route('shop.logout', $shop->slug) }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('shop.login', $shop->slug) }}" class="text-gray-700 hover:text-pink-600">
                            <i class="fas fa-user text-xl"></i>
                        </a>
                    @endif
                </nav>
            </div>

            <!-- Categories Nav -->
            <nav class="flex items-center space-x-6 py-2 overflow-x-auto">
                <a href="{{ route('shop.show', $shop->slug) }}" class="text-sm text-gray-600 hover:text-pink-600 whitespace-nowrap {{ request()->routeIs('shop.show') ? 'text-pink-600 font-medium' : '' }}">
                    Home
                </a>
                @foreach($shop->categories->where('parent_id', null)->sortBy('name') as $category)
                    <a href="{{ route('shop.category', [$shop->slug, $category->slug]) }}" 
                       class="text-sm text-gray-600 hover:text-pink-600 whitespace-nowrap {{ request()->routeIs('shop.category') && request()->route('category')?->slug === $category->slug ? 'text-pink-600 font-medium' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
                @if($shop->categories->where('parent_id', null)->count() > 6)
                    <a href="{{ route('shop.categories', $shop->slug) }}" class="text-sm text-pink-600 whitespace-nowrap">
                        All Categories <i class="fas fa-chevron-right text-xs"></i>
                    </a>
                @endif
            </nav>
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Shop Info -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-4">{{ $shop->name }}</h3>
                    <p class="text-sm mb-4">{{ $shop->description }}</p>
                    @if($shop->whatsapp_number)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $shop->whatsapp_number) }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                            <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                        </a>
                    @endif
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-white font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('shop.show', $shop->slug) }}" class="hover:text-white">Home</a></li>
                        <li><a href="{{ route('shop.products', $shop->slug) }}" class="hover:text-white">All Products</a></li>
                        <li><a href="{{ route('shop.offers', $shop->slug) }}" class="hover:text-white">Offers</a></li>
                        <li><a href="{{ route('shop.contact', $shop->slug) }}" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div>
                    <h3 class="text-white font-bold mb-4">Categories</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach($shop->categories->take(5) as $category)
                            <li><a href="{{ route('shop.category', [$shop->slug, $category->slug]) }}" class="hover:text-white">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-white font-bold mb-4">Contact Us</h3>
                    <ul class="space-y-2 text-sm">
                        @if($shop->email)
                            <li><i class="fas fa-envelope mr-2"></i> {{ $shop->email }}</li>
                        @endif
                        @if($shop->phone)
                            <li><i class="fas fa-phone mr-2"></i> {{ $shop->phone }}</li>
                        @endif
                        @if($shop->address)
                            <li><i class="fas fa-map-marker-alt mr-2"></i> {{ $shop->address }}</li>
                        @endif
                    </ul>
                    
                    <!-- Social Links -->
                    @if($shop->facebook_url || $shop->instagram_url)
                        <div class="flex space-x-4 mt-4">
                            @if($shop->facebook_url)
                                <a href="{{ $shop->facebook_url }}" target="_blank" class="text-xl hover:text-white"><i class="fab fa-facebook"></i></a>
                            @endif
                            @if($shop->instagram_url)
                                <a href="{{ $shop->instagram_url }}" target="_blank" class="text-xl hover:text-white"><i class="fab fa-instagram"></i></a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} {{ $shop->name }}. All rights reserved.</p>
                <p class="text-gray-500 mt-2">Powered by {{ config('app.name') }}</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float Button -->
    @if($shop->whatsapp_number)
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $shop->whatsapp_number) }}?text={{ urlencode('Hello! I have a question about your products.') }}" 
           target="_blank"
           class="fixed bottom-6 right-6 w-14 h-14 bg-green-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-green-600 transition-all hover:scale-110 z-50">
            <i class="fab fa-whatsapp text-2xl"></i>
        </a>
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle add to cart and remove from cart forms
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form.classList.contains('add-to-cart-form') || form.classList.contains('cart-remove-form')) {
                    e.preventDefault();
                    
                    const formData = new FormData(form);
                    const method = form.querySelector('input[name="_method"]')?.value || 'POST';
                    
                    fetch(form.action, {
                        method: method,
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Cart response:', data); // Debug logging
                        if (data.success) {
                            // Update cart count
                            const cartCount = document.getElementById('cart-count');
                            if (cartCount) {
                                cartCount.textContent = data.cart_count;
                            }
                            
                            // If removing item, remove it from the DOM
                            if (form.classList.contains('cart-remove-form')) {
                                const productId = form.querySelector('input[name="product_id"]').value;
                                const cartItem = document.getElementById('cart-item-' + productId);
                                if (cartItem) {
                                    cartItem.remove();
                                    
                                    // Check if cart is now empty
                                    const cartItems = document.querySelectorAll('[id^="cart-item-"]');
                                    if (cartItems.length === 1) { // Only the one we just removed
                                        location.reload(); // Reload to show empty cart message
                                    }
                                }
                            }
                            
                            // Show success message
                            showToast(data.message, 'success');
                        } else {
                            showToast(data.message || 'Error processing cart', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // If AJAX fails, submit the form normally
                        form.submit();
                    });
                }
            });
            
            function showToast(message, type) {
                // Create toast element
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
                toast.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                        <span>${message}</span>
                        <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                
                document.body.appendChild(toast);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                }, 5000);
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
