@extends('layouts.admin')

@section('title', 'Shop: ' . $shop->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('admin.shops.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $shop->name }}</h1>
                <p class="text-gray-600">Shop details and statistics</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.shops.subscription', $shop) }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                <i class="fas fa-crown mr-2"></i> Manage Subscription
            </a>
            <a href="{{ route('admin.shops.edit', $shop) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Shop Info & Stats -->
    <div class="grid grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6 col-span-1">
            <div class="flex items-center justify-center mb-4">
                @if($shop->logo)
                    <img src="{{ Storage::url($shop->logo) }}" alt="{{ $shop->name }}" class="w-24 h-24 rounded-full object-cover">
                @else
                    <div class="w-24 h-24 rounded-full bg-pink-100 flex items-center justify-center">
                        <i class="fas fa-store text-3xl text-pink-500"></i>
                    </div>
                @endif
            </div>
            <h2 class="text-lg font-semibold text-center">{{ $shop->name }}</h2>
            <p class="text-gray-500 text-sm text-center">{{ $shop->slug }}</p>
            
            <div class="mt-4 space-y-2 text-sm">
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-user w-6"></i>
                    <span>{{ $shop->user->name ?? 'N/A' }}</span>
                </div>
                @if($shop->email)
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-envelope w-6"></i>
                    <span>{{ $shop->email }}</span>
                </div>
                @endif
                @if($shop->phone)
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-phone w-6"></i>
                    <span>{{ $shop->phone }}</span>
                </div>
                @endif
                @if($shop->whatsapp)
                <div class="flex items-center text-gray-600">
                    <i class="fab fa-whatsapp w-6 text-green-500"></i>
                    <a href="{{ $shop->whatsapp_link }}" target="_blank" class="text-green-600 hover:underline">
                        {{ $shop->whatsapp }}
                    </a>
                </div>
                @endif
                @if($shop->city)
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-map-marker-alt w-6"></i>
                    <span>{{ $shop->city }}, {{ $shop->country ?? 'Pakistan' }}</span>
                </div>
                @endif
            </div>

            <div class="mt-4 pt-4 border-t">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium w-full justify-center
                    {{ $shop->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $shop->status === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                    {{ $shop->status === 'suspended' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($shop->status) }}
                </span>
            </div>
        </div>

        <div class="col-span-3 grid grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Products</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_products'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-box text-blue-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Orders</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_orders'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-green-500 text-xl"></i>
                    </div>
                </div>
                @if($stats['pending_orders'] > 0)
                    <p class="text-sm text-yellow-600 mt-2">
                        <i class="fas fa-clock"></i> {{ $stats['pending_orders'] }} pending
                    </p>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Customers</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_customers'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-users text-purple-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Revenue</p>
                        <p class="text-3xl font-bold text-gray-800">Rs. {{ number_format($stats['total_revenue']) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-pink-100 flex items-center justify-center">
                        <i class="fas fa-rupee-sign text-pink-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Active Offers</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['active_offers'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                        <i class="fas fa-tags text-orange-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-medium text-gray-700 mb-3">Subscription</h3>
                @if($shop->activeSubscription)
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $shop->activeSubscription->status_badge }}">
                            @if($shop->activeSubscription->isOnTrial())
                                <i class="fas fa-clock mr-1"></i> Trial
                            @else
                                <i class="fas fa-crown mr-1 text-yellow-500"></i> {{ $shop->activeSubscription->plan->name ?? 'Active' }}
                            @endif
                        </span>
                        <span class="text-sm text-gray-600">{{ $shop->activeSubscription->daysRemaining() }} days left</span>
                    </div>
                    @if($shop->activeSubscription->plan)
                        <p class="text-sm text-gray-500 mt-2">{{ $shop->activeSubscription->plan->price_display }}</p>
                    @endif
                @else
                    <p class="text-gray-400">No active subscription</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Recent Orders</h3>
            @if($shop->orders->count() > 0)
                <div class="space-y-3">
                    @foreach($shop->orders as $order)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium">{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-500">{{ $order->customer->name ?? 'Guest' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium">Rs. {{ number_format($order->total) }}</p>
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
                <p class="text-gray-400 text-center py-4">No orders yet</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Recent Products</h3>
            @if($shop->products->count() > 0)
                <div class="space-y-3">
                    @foreach($shop->products as $product)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            @if($product->featured_image)
                                <img src="{{ Storage::url($product->featured_image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded object-cover mr-3">
                            @else
                                <div class="w-12 h-12 rounded bg-gray-200 flex items-center justify-center mr-3">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <p class="font-medium">{{ Str::limit($product->name, 30) }}</p>
                                <p class="text-sm text-gray-500">Rs. {{ number_format($product->price) }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full
                                {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-center py-4">No products yet</p>
            @endif
        </div>
    </div>
</div>
@endsection
