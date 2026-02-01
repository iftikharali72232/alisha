@extends('user.shop.layout')

@section('title', 'Order #' . $order->order_number)
@section('page-title', 'Order Details')

@section('shop-content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Order #{{ $order->order_number }}</h1>
            <p class="text-gray-600">{{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('user.shop.orders.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                <i class="fas fa-print mr-2"></i> Print
            </button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b">
                <h3 class="font-semibold text-gray-800">Order Items</h3>
            </div>
            <div class="p-4">
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                            @if($item->product?->featured_image)
                                <img src="{{ Storage::url($item->product->featured_image) }}" alt="{{ $item->product_name }}" class="w-16 h-16 rounded object-cover">
                            @else
                                <div class="w-16 h-16 rounded bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                                @if($item->variant_name)
                                    <p class="text-sm text-gray-500">{{ $item->variant_name }}</p>
                                @endif
                                <p class="text-sm text-gray-500">SKU: {{ $item->product?->sku ?? 'N/A' }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-500">Qty</p>
                                <p class="font-medium">{{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Price</p>
                                <p class="font-medium">Rs. {{ number_format($item->price) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Total</p>
                                <p class="font-medium">Rs. {{ number_format($item->total) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b">
                <h3 class="font-semibold text-gray-800">Order Summary</h3>
            </div>
            <div class="p-4">
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">Rs. {{ number_format($order->subtotal) }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Discount</span>
                            <span>- Rs. {{ number_format($order->discount) }}</span>
                        </div>
                    @endif
                    @if($order->tax > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span>Rs. {{ number_format($order->tax) }}</span>
                        </div>
                    @endif
                    @if($order->shipping_cost > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span>Rs. {{ number_format($order->shipping_cost) }}</span>
                        </div>
                    @endif
                    <div class="border-t pt-2 flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span class="text-pink-600">Rs. {{ number_format($order->total) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Note -->
        @if($order->notes)
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 border-b">
                    <h3 class="font-semibold text-gray-800">Customer Note</h3>
                </div>
                <div class="p-4">
                    <p class="text-gray-600">{{ $order->notes }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Order Status -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b">
                <h3 class="font-semibold text-gray-800">Order Status</h3>
            </div>
            <div class="p-4">
                <form action="{{ route('user.shop.orders.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <select name="status" onchange="this.form.submit()"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500
                            {{ $order->status === 'pending' ? 'bg-yellow-50' : '' }}
                            {{ $order->status === 'processing' ? 'bg-blue-50' : '' }}
                            {{ $order->status === 'shipped' ? 'bg-purple-50' : '' }}
                            {{ $order->status === 'delivered' ? 'bg-green-50' : '' }}
                            {{ $order->status === 'cancelled' ? 'bg-red-50' : '' }}">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </form>

                <div class="mt-4 pt-4 border-t">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Payment Status</span>
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $order->payment_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Payment Method</span>
                        <span class="text-sm font-medium">{{ ucfirst($order->payment_method) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b">
                <h3 class="font-semibold text-gray-800">Customer</h3>
            </div>
            <div class="p-4">
                @if($order->customer)
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center">
                            <span class="text-pink-600 font-medium">{{ substr($order->customer->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $order->customer->name }}</p>
                            <p class="text-sm text-gray-500">{{ $order->customer->orders->count() }} orders</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-envelope w-5"></i>
                            <span>{{ $order->customer->email ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-phone w-5"></i>
                            <span>{{ $order->customer->phone ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <a href="{{ route('user.shop.customers.show', $order->customer) }}" class="mt-4 block text-center py-2 border rounded-lg hover:bg-gray-50 text-sm text-pink-600">
                        View Customer Profile
                    </a>
                @else
                    <p class="text-gray-500">Guest Customer</p>
                    <div class="mt-2 space-y-1 text-sm text-gray-600">
                        <p><strong>Name:</strong> {{ $order->billing_name }}</p>
                        <p><strong>Email:</strong> {{ $order->billing_email }}</p>
                        <p><strong>Phone:</strong> {{ $order->billing_phone }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Billing Address -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b">
                <h3 class="font-semibold text-gray-800">Billing Address</h3>
            </div>
            <div class="p-4 text-sm text-gray-600">
                <p class="font-medium text-gray-900">{{ $order->billing_name }}</p>
                <p>{{ $order->billing_address }}</p>
                @if($order->billing_address_2)
                    <p>{{ $order->billing_address_2 }}</p>
                @endif
                <p>{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_zip }}</p>
                <p>{{ $order->billing_country }}</p>
                @if($order->billing_phone)
                    <p class="mt-2"><i class="fas fa-phone mr-1"></i> {{ $order->billing_phone }}</p>
                @endif
            </div>
        </div>

        <!-- Shipping Address -->
        @if($order->shipping_name)
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 border-b">
                    <h3 class="font-semibold text-gray-800">Shipping Address</h3>
                </div>
                <div class="p-4 text-sm text-gray-600">
                    <p class="font-medium text-gray-900">{{ $order->shipping_name }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    @if($order->shipping_address_2)
                        <p>{{ $order->shipping_address_2 }}</p>
                    @endif
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                    <p>{{ $order->shipping_country }}</p>
                    @if($order->shipping_phone)
                        <p class="mt-2"><i class="fas fa-phone mr-1"></i> {{ $order->shipping_phone }}</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- WhatsApp Contact -->
        @if($order->billing_phone || $order->customer?->phone)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->billing_phone ?? $order->customer->phone) }}" 
               target="_blank"
               class="block w-full text-center py-3 bg-green-500 text-white rounded-lg hover:bg-green-600">
                <i class="fab fa-whatsapp mr-2"></i> Contact via WhatsApp
            </a>
        @endif
    </div>
</div>
@endsection
