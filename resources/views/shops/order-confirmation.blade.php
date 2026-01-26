@extends('shops.layout')

@section('title', 'Order Confirmation - ' . $shop->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-green-50 px-6 py-4 border-b">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h1 class="text-xl font-semibold text-gray-900">Order Confirmed!</h1>
                    <p class="text-gray-600">Thank you for your order. Your order has been successfully placed.</p>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Order Info -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Number:</span>
                            <span class="font-medium">{{ $order->order_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Date:</span>
                            <span class="font-medium">{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h2>
                    <div class="text-sm text-gray-600">
                        <p class="font-medium text-gray-900">{{ $order->customer->name }}</p>
                        <p>{{ $order->customer->email }}</p>
                        @if($order->customer->phone)
                            <p>{{ $order->customer->phone }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h2>
                <div class="border rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center">
                                            @if($item->product->featured_image)
                                                <img src="{{ Storage::url($item->product->featured_image) }}" alt="{{ $item->product->name }}" class="w-12 h-12 rounded-lg object-cover">
                                            @endif
                                            <div class="ml-4">
                                                <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                                @if($item->variant)
                                                    <p class="text-sm text-gray-500">{{ $item->variant->name }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">{{ $item->quantity }}</td>
                                    <td class="px-4 py-4 text-right">Rs. {{ number_format($item->price) }}</td>
                                    <td class="px-4 py-4 text-right font-medium">Rs. {{ number_format($item->price * $item->quantity) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="mt-6 flex justify-end">
                <div class="w-64">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span>Rs. {{ number_format($order->subtotal) }}</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount{{ $order->coupon_code ? ' (' . $order->coupon_code . ')' : '' }}:</span>
                                <span>-Rs. {{ number_format($order->discount) }}</span>
                            </div>
                        @endif
                        @if($order->tax > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax:</span>
                                <span>Rs. {{ number_format($order->tax) }}</span>
                            </div>
                        @endif
                        <div class="border-t pt-2 flex justify-between font-semibold text-lg">
                            <span>Total:</span>
                            <span>Rs. {{ number_format($order->total) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-gray-50 px-6 py-4 border-t">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    <p>You will receive an email confirmation shortly.</p>
                    <p>For any questions, contact us at {{ $shop->email ?? 'support@' . $shop->slug . '.com' }}</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('shop.show', $shop->slug) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        Continue Shopping
                    </a>
                    <a href="{{ route('shop.orders', $shop->slug) }}" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                        View My Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection