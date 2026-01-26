@extends('shops.layout')

@section('title', 'My Orders - ' . $shop->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">My Orders</h1>
        <a href="{{ route('shop.account', $shop->slug) }}" class="text-pink-600 hover:text-pink-700">
            <i class="fas fa-arrow-left mr-2"></i>Back to Account
        </a>
    </div>

    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <!-- Order Header -->
                    <div class="px-6 py-4 border-b bg-gray-50 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="font-semibold text-gray-800">Order #{{ $order->order_number }}</p>
                            <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                            <p class="mt-1 font-semibold text-gray-800">{{ $shop->currency }} {{ number_format($order->total, 2) }}</p>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="divide-y">
                        @foreach($order->items as $item)
                            <div class="px-6 py-4 flex items-center gap-4">
                                @if($item->product?->featured_image)
                                    <img src="{{ Storage::url($item->product->featured_image) }}" alt="{{ $item->product_name }}"
                                        class="w-16 h-16 rounded-lg object-cover">
                                @else
                                    <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-box text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">{{ $item->product_name }}</p>
                                    @if($item->variant_name)
                                        <p class="text-sm text-gray-500">{{ $item->variant_name }}</p>
                                    @endif
                                    <p class="text-sm text-gray-600">
                                        {{ $shop->currency }} {{ number_format($item->price, 2) }} × {{ $item->quantity }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-800">{{ $shop->currency }} {{ number_format($item->total, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t flex flex-wrap items-center justify-between gap-4">
                        <!-- Shipping Info -->
                        <div class="text-sm">
                            <p class="text-gray-500">Ship to:</p>
                            <p class="text-gray-800">
                                {{ $order->shipping_address }}, {{ $order->shipping_city }}
                            </p>
                        </div>

                        <!-- Order Summary -->
                        <div class="text-right text-sm space-y-1">
                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Subtotal:</span>
                                <span>{{ $shop->currency }} {{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            @if($order->discount > 0)
                                <div class="flex justify-between gap-4 text-green-600">
                                    <span>Discount:</span>
                                    <span>-{{ $shop->currency }} {{ number_format($order->discount, 2) }}</span>
                                </div>
                            @endif
                            @if($order->shipping_fee > 0)
                                <div class="flex justify-between gap-4">
                                    <span class="text-gray-500">Shipping:</span>
                                    <span>{{ $shop->currency }} {{ number_format($order->shipping_fee, 2) }}</span>
                                </div>
                            @endif
                            @if($order->tax > 0)
                                <div class="flex justify-between gap-4">
                                    <span class="text-gray-500">Tax:</span>
                                    <span>{{ $shop->currency }} {{ number_format($order->tax, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between gap-4 font-semibold pt-1 border-t">
                                <span>Total:</span>
                                <span>{{ $shop->currency }} {{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($shop->whatsapp && $order->status !== 'delivered' && $order->status !== 'cancelled')
                        <div class="px-6 py-3 border-t">
                            <a href="https://wa.me/{{ $shop->whatsapp }}?text={{ urlencode('Hi! I would like to know the status of my order #' . $order->order_number) }}"
                                target="_blank"
                                class="inline-flex items-center text-green-600 hover:text-green-700">
                                <i class="fab fa-whatsapp mr-2"></i>Ask about this order
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-shopping-bag text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-700 mb-2">No orders yet</h3>
            <p class="text-gray-500 mb-4">Start shopping to see your orders here</p>
            <a href="{{ route('shop.products', $shop->slug) }}"
                class="inline-block px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection
