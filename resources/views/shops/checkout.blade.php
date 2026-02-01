@extends('shops.layout')

@section('title', 'Checkout - ' . $shop->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Checkout</h1>

    <form action="{{ route('shop.checkout.process', $shop->slug) }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Info -->
                @if(!$customer)
                    <div class="bg-white rounded-xl shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-gray-800">Contact Information</h2>
                            <a href="{{ route('shop.login', $shop->slug) }}" class="text-pink-600 text-sm hover:underline">
                                Already have an account? Login
                            </a>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                <input type="text" name="billing_name" value="{{ old('billing_name') }}" required
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('billing_name') border-red-500 @enderror">
                                @error('billing_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="billing_email" value="{{ old('billing_email') }}"
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                                <input type="tel" name="billing_phone" value="{{ old('billing_phone') }}" required
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('billing_phone') border-red-500 @enderror">
                                @error('billing_phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Contact Information</h2>
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-pink-100 flex items-center justify-center mr-4">
                                <span class="text-pink-600 font-bold text-lg">{{ substr($customer->name ?? 'G', 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $customer->name ?? 'Guest' }}</p>
                                <p class="text-gray-500 text-sm">{{ $customer->email ?? $customer->phone ?? '' }}</p>
                            </div>
                        </div>
                        <input type="hidden" name="billing_name" value="{{ $customer->name ?? '' }}">
                        <input type="hidden" name="billing_email" value="{{ $customer->email ?? '' }}">
                        <input type="hidden" name="billing_phone" value="{{ $customer->phone ?? '' }}">
                    </div>
                @endif

                <!-- Billing Address -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Billing Address</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                            <input type="text" name="billing_address" value="{{ old('billing_address') }}" required
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('billing_address') border-red-500 @enderror"
                                placeholder="House/Flat number, Street">
                            @error('billing_address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                            <input type="text" name="billing_address_2" value="{{ old('billing_address_2') }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                                placeholder="Area, Landmark (optional)">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                                <select name="billing_city" required
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ old('billing_city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                                <select name="billing_state"
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                                    <option value="">Select State/Province</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state }}" {{ old('billing_state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ZIP/Postal Code</label>
                                <input type="text" name="billing_zip" value="{{ old('billing_zip') }}"
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                            </div>
                        </div>
                        <input type="hidden" name="billing_country" value="Pakistan">
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-xl shadow p-6" x-data="{ sameAsBilling: true }">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Shipping Address</h2>
                        <label class="flex items-center">
                            <input type="checkbox" x-model="sameAsBilling" name="shipping_same" value="1" checked
                                class="rounded text-pink-500 focus:ring-pink-500 mr-2">
                            <span class="text-sm text-gray-600">Same as billing</span>
                        </label>
                    </div>
                    
                    <div x-show="!sameAsBilling" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Recipient Name</label>
                            <input type="text" name="shipping_name" value="{{ old('shipping_name') }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" name="shipping_phone" value="{{ old('shipping_phone') }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <input type="text" name="shipping_address" value="{{ old('shipping_address') }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <select name="shipping_city"
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ old('shipping_city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                                <select name="shipping_state"
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state }}" {{ old('shipping_state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ZIP Code</label>
                                <input type="text" name="shipping_zip" value="{{ old('shipping_zip') }}"
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                            </div>
                        </div>
                        <input type="hidden" name="shipping_country" value="Pakistan">
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Payment Method</h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="cod" checked
                                class="text-pink-500 focus:ring-pink-500 mr-3">
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">Cash on Delivery</p>
                                <p class="text-sm text-gray-500">Pay when you receive your order</p>
                            </div>
                            <i class="fas fa-money-bill-wave text-2xl text-green-500"></i>
                        </label>
                        
                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="bank_transfer"
                                class="text-pink-500 focus:ring-pink-500 mr-3">
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">Bank Transfer</p>
                                <p class="text-sm text-gray-500">Transfer to our bank account</p>
                            </div>
                            <i class="fas fa-university text-2xl text-blue-500"></i>
                        </label>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Order Notes (Optional)</h2>
                    <textarea name="notes" rows="3" 
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="Any special instructions for your order...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow p-6 sticky top-20">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Order Summary</h2>

                    <!-- Cart Items -->
                    <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                        @foreach($cart as $id => $item)
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0">
                                    @if($item['image'])
                                        <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-300 text-xs"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-800 line-clamp-1">{{ $item['name'] }}</p>
                                    <p class="text-xs text-gray-500">Qty: {{ $item['quantity'] }}</p>
                                </div>
                                <p class="text-sm font-medium">Rs. {{ number_format($item['price'] * $item['quantity']) }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span>Rs. {{ number_format($subtotal) }}</span>
                        </div>
                        
                        @if($discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount</span>
                                <span>- Rs. {{ number_format($discount) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span>{{ $shipping > 0 ? 'Rs. ' . number_format($shipping) : 'Free' }}</span>
                        </div>

                        @if($tax > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax</span>
                                <span>Rs. {{ number_format($tax) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="border-t mt-4 pt-4">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span class="text-pink-600">Rs. {{ number_format($total) }}</span>
                        </div>
                    </div>

                    <!-- Coupon Code -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Have a coupon code?</label>
                        <input type="text" name="coupon_code" value="{{ old('coupon_code') }}"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-pink-500 focus:border-pink-500"
                            placeholder="Enter coupon code (applied during checkout)">
                    </div>

                    <!-- Loyalty Points -->
                    @if(session('shop_customer_' . $shop->id) && ($shop->loyaltySetting?->is_enabled ?? false))
                        @php
                            $customer = \App\Models\ShopCustomer::find(session('shop_customer_' . $shop->id)['id']);
                            $earnablePoints = floor($total / 100) * ($shop->loyaltySetting->points_per_currency ?? 1);
                        @endphp
                        
                        @if($customer && $customer->loyalty_points > 0)
                            <div class="mt-4 p-3 bg-yellow-50 rounded-lg text-sm">
                                <p class="font-medium text-yellow-800 mb-2">
                                    <i class="fas fa-star mr-1"></i> You have {{ $customer->loyalty_points }} points
                                </p>
                                <label class="flex items-center">
                                    <input type="checkbox" name="use_loyalty_points" value="1" class="rounded text-yellow-500 focus:ring-yellow-500 mr-2">
                                    <span class="text-yellow-700">Use points for discount</span>
                                </label>
                            </div>
                        @endif
                        
                        <div class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Earn {{ $earnablePoints }} points with this order
                        </div>
                    @endif

                    <button type="submit" class="mt-6 w-full py-3 bg-pink-600 text-white rounded-lg font-medium hover:bg-pink-700">
                        Place Order
                    </button>

                    <p class="mt-4 text-xs text-gray-500 text-center">
                        By placing this order, you agree to our Terms & Conditions
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
