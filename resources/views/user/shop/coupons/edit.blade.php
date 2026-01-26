@extends('user.shop.layout')

@section('title', 'Edit Coupon - ' . $shop->name)

@section('shop-content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Coupon</h1>
            <p class="text-gray-600">Update coupon information</p>
        </div>
        <a href="{{ route('user.shop.coupons.index') }}"
            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Coupons
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
@endif

<form action="{{ route('user.shop.coupons.update', $coupon) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Coupon Code -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Coupon Code</h3>

                <div class="space-y-4">
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Code *</label>
                        <div class="flex space-x-2">
                            <input type="text" name="code" id="code" value="{{ old('code', $coupon->code) }}" required
                                class="flex-1 border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 font-mono uppercase @error('code') border-red-500 @enderror"
                                placeholder="e.g., SUMMER2024">
                            <button type="button" onclick="generateCode()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                                <i class="fas fa-random mr-1"></i> Generate
                            </button>
                        </div>
                        @error('code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $coupon->name) }}" required
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <input type="text" name="description" id="description" value="{{ old('description', $coupon->description) }}"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="e.g., Summer sale discount">
                    </div>
                </div>
            </div>

            <!-- Discount Settings -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Discount Settings</h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Discount Type *</label>
                            <select name="type" id="type" required
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('type') border-red-500 @enderror">
                                <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                <option value="free_shipping" {{ old('type', $coupon->type) == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="valueField" class="{{ $coupon->type == 'free_shipping' ? 'hidden' : '' }}">
                            <label for="value" class="block text-sm font-medium text-gray-700 mb-1">Discount Value *</label>
                            <input type="number" name="value" id="value" step="0.01" value="{{ old('value', $coupon->value) }}" {{ $coupon->type == 'free_shipping' ? '' : 'required' }}
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('value') border-red-500 @enderror">
                            @error('value')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="minimum_purchase" class="block text-sm font-medium text-gray-700 mb-1">Minimum Purchase</label>
                            <input type="number" name="minimum_purchase" id="minimum_purchase" step="0.01" value="{{ old('minimum_purchase', $coupon->min_order_amount) }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                                placeholder="0.00">
                        </div>

                        <div id="maxDiscountField" class="{{ $coupon->type != 'percentage' ? 'hidden' : '' }}">
                            <label for="maximum_discount" class="block text-sm font-medium text-gray-700 mb-1">Max Discount Amount</label>
                            <input type="number" name="maximum_discount" id="maximum_discount" step="0.01" value="{{ old('maximum_discount', $coupon->max_discount_amount) }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                                placeholder="0.00">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Limits -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Usage Limits</h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="usage_limit" class="block text-sm font-medium text-gray-700 mb-1">Total Usage Limit</label>
                            <input type="number" name="usage_limit" id="usage_limit" min="1" value="{{ old('usage_limit', $coupon->usage_limit) }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                                placeholder="Unlimited">
                        </div>

                        <div>
                            <label for="usage_limit_per_customer" class="block text-sm font-medium text-gray-700 mb-1">Per Customer Limit *</label>
                            <input type="number" name="usage_limit_per_customer" id="usage_limit_per_customer" min="1" value="{{ old('usage_limit_per_customer', $coupon->usage_limit_per_customer) }}" required
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('usage_limit_per_customer') border-red-500 @enderror">
                            @error('usage_limit_per_customer')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status & Dates -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Status & Validity</h3>

                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                            <p class="text-xs text-gray-500 mt-1">Leave empty to start immediately</p>
                        </div>

                        <div>
                            <label for="ends_at" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', $coupon->ends_at ? $coupon->ends_at->format('Y-m-d\TH:i') : '') }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                            <p class="text-xs text-gray-500 mt-1">Leave empty for no expiry</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full bg-pink-500 text-white py-2 px-4 rounded-lg hover:bg-pink-600 transition">
                        <i class="fas fa-save mr-2"></i>Update Coupon
                    </button>
                    <a href="{{ route('user.shop.coupons.index') }}" class="w-full bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition text-center">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function generateCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < 8; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('code').value = code;
}

document.getElementById('type').addEventListener('change', function() {
    const type = this.value;
    const valueField = document.getElementById('valueField');
    const maxDiscountField = document.getElementById('maxDiscountField');
    const valueInput = document.getElementById('value');

    if (type === 'free_shipping') {
        valueField.classList.add('hidden');
        valueInput.required = false;
        maxDiscountField.classList.add('hidden');
    } else {
        valueField.classList.remove('hidden');
        valueInput.required = true;
        maxDiscountField.classList.toggle('hidden', type !== 'percentage');
    }
});
</script>
@endsection