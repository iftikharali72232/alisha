@extends('user.shop.layout')

@section('title', 'Shop Dashboard')

@section('shop-content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Shop Dashboard</h1>
            <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}!</p>
        </div>
        <a href="{{ route('shop.show', $shop->slug) }}" target="_blank" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700">
            <i class="fas fa-external-link-alt mr-2"></i> View Shop
        </a>
    </div>
</div>

<!-- Subscription Alert -->
@if($shop->activeSubscription)
    @if($shop->activeSubscription->isOnTrial())
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-clock mr-3 text-xl"></i>
                <div>
                    <p class="font-medium">Trial Period</p>
                    <p class="text-sm">{{ $shop->activeSubscription->daysRemaining() }} days remaining in your free trial.</p>
                </div>
            </div>
            <a href="{{ route('user.shop.subscription') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                Upgrade Now
            </a>
        </div>
    @elseif($shop->activeSubscription->daysRemaining() <= 7)
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-3 text-xl"></i>
                <div>
                    <p class="font-medium">Subscription Expiring Soon</p>
                    <p class="text-sm">Your subscription expires in {{ $shop->activeSubscription->daysRemaining() }} days.</p>
                </div>
            </div>
            <a href="{{ route('user.shop.subscription') }}" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 text-sm">
                Renew Now
            </a>
        </div>
    @endif
@else
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <i class="fas fa-ban mr-3 text-xl"></i>
            <div>
                <p class="font-medium">No Active Subscription</p>
                <p class="text-sm">Your shop features are limited. Please subscribe to continue.</p>
            </div>
        </div>
        <a href="{{ route('user.shop.subscription') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm">
            Subscribe Now
        </a>
    </div>
@endif

<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Products</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_products'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="fas fa-box text-blue-500 text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-2">{{ $stats['active_products'] }} active</p>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Orders</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_orders'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                <i class="fas fa-shopping-cart text-green-500 text-xl"></i>
            </div>
        </div>
        @if($stats['pending_orders'] > 0)
            <p class="text-xs text-yellow-600 mt-2"><i class="fas fa-clock"></i> {{ $stats['pending_orders'] }} pending</p>
        @else
            <p class="text-xs text-gray-400 mt-2">No pending orders</p>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Customers</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_customers'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                <i class="fas fa-users text-purple-500 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Revenue</p>
                <p class="text-2xl font-bold text-gray-800">Rs. {{ number_format($stats['total_revenue']) }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-pink-100 flex items-center justify-center">
                <i class="fas fa-rupee-sign text-pink-500 text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-2">Today: Rs. {{ number_format($stats['today_revenue']) }}</p>
    </div>
</div>

<!-- Today's Stats -->
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-gradient-to-r from-pink-500 to-pink-600 rounded-lg shadow p-4 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-pink-100 text-sm">Today's Orders</p>
                <p class="text-3xl font-bold">{{ $stats['today_orders'] }}</p>
            </div>
            <i class="fas fa-shopping-bag text-4xl text-pink-300"></i>
        </div>
    </div>

    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow p-4 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Active Promotions</p>
                <p class="text-3xl font-bold">{{ $stats['active_offers'] + $stats['active_coupons'] }}</p>
            </div>
            <i class="fas fa-tags text-4xl text-purple-300"></i>
        </div>
    </div>
</div>

<!-- Recent Orders & Top Products -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-800">Recent Orders</h3>
            <a href="{{ route('user.shop.orders.index') }}" class="text-pink-600 text-sm hover:underline">View All</a>
        </div>
        
        @if($recentOrders->count() > 0)
            <div class="space-y-3">
                @foreach($recentOrders as $order)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-sm">{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-500">{{ $order->customer->name ?? 'Guest' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-sm">Rs. {{ number_format($order->total) }}</p>
                            <span class="text-xs px-2 py-1 rounded-full
                                {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-shopping-cart text-4xl mb-3"></i>
                <p>No orders yet</p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-800">Top Products</h3>
            <a href="{{ route('user.shop.products.index') }}" class="text-pink-600 text-sm hover:underline">View All</a>
        </div>
        
        @if($topProducts->count() > 0)
            <div class="space-y-3">
                @foreach($topProducts as $product)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        @if($product->featured_image)
                            <img src="{{ Storage::url($product->featured_image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded object-cover mr-3">
                        @else
                            <div class="w-12 h-12 rounded bg-gray-200 flex items-center justify-center mr-3">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-sm">{{ Str::limit($product->name, 25) }}</p>
                            <p class="text-xs text-gray-500">Rs. {{ number_format($product->price) }}</p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $product->order_items_count }} sold</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-box text-4xl mb-3"></i>
                <p>No products yet</p>
                <a href="{{ route('user.shop.products.create') }}" class="text-pink-600 text-sm hover:underline mt-2 inline-block">
                    Add your first product
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-6 bg-white rounded-lg shadow p-4">
    <h3 class="font-semibold text-gray-800 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('user.shop.products.create') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-pink-50 transition">
            <div class="w-12 h-12 rounded-full bg-pink-100 flex items-center justify-center mb-2">
                <i class="fas fa-plus text-pink-500"></i>
            </div>
            <span class="text-sm text-gray-700">Add Product</span>
        </a>
        <a href="{{ route('user.shop.orders.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-pink-50 transition">
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mb-2">
                <i class="fas fa-list text-green-500"></i>
            </div>
            <span class="text-sm text-gray-700">View Orders</span>
        </a>
        <a href="{{ route('user.shop.offers.create') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-pink-50 transition {{ !$shop->activeSubscription?->plan?->loyalty_enabled ? 'opacity-50 pointer-events-none' : '' }}">
            <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center mb-2">
                <i class="fas fa-tags text-orange-500"></i>
                @if(!$shop->activeSubscription?->plan?->loyalty_enabled)
                    <span class="absolute -top-1 -right-1 bg-yellow-500 text-white text-xs px-1 rounded">PRO</span>
                @endif
            </div>
            <span class="text-sm text-gray-700">Create Offer</span>
        </a>
        <a href="{{ route('user.shop.settings.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-pink-50 transition">
            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-2">
                <i class="fas fa-cog text-gray-500"></i>
            </div>
            <span class="text-sm text-gray-700">Shop Settings</span>
        </a>
    </div>
</div>
@endsection
