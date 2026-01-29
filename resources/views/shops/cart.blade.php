@extends('shops.layout')

@section('title', 'Shopping Cart - ' . $shop->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Shopping Cart</h1>

    @if(count($cart) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    @foreach($cart as $id => $item)
                        <div class="flex items-center p-4 border-b last:border-b-0" id="cart-item-{{ $id }}">
                            <!-- Product Image -->
                            <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
                                @if($item['image'])
                                    <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-300"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Details -->
                            <div class="ml-4 flex-1">
                                <h3 class="font-medium text-gray-800">{{ $item['name'] }}</h3>
                                @if(isset($item['variant']))
                                    <p class="text-sm text-gray-500">{{ $item['variant'] }}</p>
                                @endif
                                <p class="text-pink-600 font-bold mt-1">Rs. {{ number_format($item['price']) }}</p>
                            </div>

                            <!-- Quantity -->
                            <div class="flex items-center">
                                <form action="{{ route('shop.cart.update', $shop->slug) }}" method="POST" class="cart-update-form">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="product_id" value="{{ $id }}">
                                    <div class="flex items-center border rounded-lg">
                                        <button type="submit" name="action" value="decrease" class="px-3 py-2 text-gray-600 hover:bg-gray-100">
                                            <i class="fas fa-minus text-sm"></i>
                                        </button>
                                        <span class="w-10 text-center">{{ $item['quantity'] }}</span>
                                        <button type="submit" name="action" value="increase" class="px-3 py-2 text-gray-600 hover:bg-gray-100">
                                            <i class="fas fa-plus text-sm"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Item Total -->
                            <div class="ml-6 text-right">
                                <p class="font-bold text-gray-800">Rs. {{ number_format($item['price'] * $item['quantity']) }}</p>
                            </div>

                            <!-- Remove -->
                            <form action="{{ route('shop.cart.remove', $shop->slug) }}" method="POST" class="cart-remove-form">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="product_id" value="{{ $id }}">
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <!-- Continue Shopping -->
                <div class="mt-4">
                    <a href="{{ route('shop.products', $shop->slug) }}" class="text-pink-600 hover:underline">
                        <i class="fas fa-arrow-left mr-2"></i> Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow p-6 sticky top-20">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Order Summary</h2>

                    @php
                        $tax = 0; // Calculate based on products
                        $total = $subtotal - $discount + $tax;
                    @endphp

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal ({{ count($cart) }} items)</span>
                            <span class="font-medium">Rs. {{ number_format($subtotal) }}</span>
                        </div>
                        
                        @if($discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount ({{ $appliedCoupon->code }})</span>
                                <span>-Rs. {{ number_format($discount) }}</span>
                            </div>
                        @endif

                        @if($tax > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax</span>
                                <span>Rs. {{ number_format($tax) }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Coupon Code -->
                    <div class="mt-4 pt-4 border-t">
                        <form action="{{ route('shop.cart.coupon', $shop->slug) }}" method="POST">
                            @csrf
                            <div class="flex gap-2">
                                <input type="text" name="coupon_code" placeholder="Coupon code" 
                                    value="{{ $appliedCoupon ? $appliedCoupon->code : '' }}"
                                    class="flex-1 border rounded-lg px-3 py-2 text-sm focus:ring-pink-500 focus:border-pink-500">
                                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm hover:bg-gray-900">
                                    Apply
                                </button>
                            </div>
                        </form>
                        @if(session('coupon_error_' . $shop->id))
                            <p class="text-red-500 text-xs mt-1">{{ session('coupon_error_' . $shop->id) }}</p>
                        @endif
                        @if($appliedCoupon)
                            <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded">
                                <p class="text-green-800 text-xs font-medium">
                                    <i class="fas fa-check-circle"></i> {{ $appliedCoupon->name }}
                                </p>
                                <p class="text-green-600 text-xs">
                                    {{ $appliedCoupon->code }} - 
                                    @if($appliedCoupon->type === 'percentage')
                                        {{ $appliedCoupon->value }}% off
                                    @else
                                        Rs. {{ number_format($appliedCoupon->value) }} off
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 pt-4 border-t">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span class="text-pink-600">Rs. {{ number_format($total) }}</span>
                        </div>
                    </div>

                    <!-- Loyalty Points Info -->
                    @if(session('shop_customer_' . $shop->id) && ($shop->loyaltySetting?->is_enabled ?? false))
                        @php
                            $earnablePoints = floor($total / 100) * ($shop->loyaltySetting->points_per_currency ?? 1);
                        @endphp
                        <div class="mt-4 p-3 bg-yellow-50 rounded-lg text-sm">
                            <i class="fas fa-star text-yellow-500 mr-1"></i>
                            Earn <strong>{{ $earnablePoints }} points</strong> with this order!
                        </div>
                    @endif

                    <a href="{{ route('shop.checkout', $shop->slug) }}" 
                       class="mt-6 block w-full py-3 bg-pink-600 text-white text-center rounded-lg font-medium hover:bg-pink-700">
                        Proceed to Checkout
                    </a>

                    <!-- WhatsApp Order -->
                    @if($shop->whatsapp_number)
                        @php
                            $cartText = "Hi! I'd like to order:\n\n";
                            foreach($cart as $id => $item) {
                                $cartText .= "• {$item['name']} x {$item['quantity']} = Rs. " . number_format($item['price'] * $item['quantity']) . "\n";
                            }
                            $cartText .= "\nTotal: Rs. " . number_format($total);
                        @endphp
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $shop->whatsapp_number) }}?text={{ urlencode($cartText) }}" 
                           target="_blank"
                           class="mt-3 block w-full py-3 bg-green-500 text-white text-center rounded-lg font-medium hover:bg-green-600">
                            <i class="fab fa-whatsapp mr-2"></i> Order via WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-16">
            <div class="w-24 h-24 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <i class="fas fa-shopping-cart text-4xl text-gray-400"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Your cart is empty</h2>
            <p class="text-gray-500 mb-6">Looks like you haven't added anything to your cart yet</p>
            <a href="{{ route('shop.products', $shop->slug) }}" class="inline-block px-6 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection
