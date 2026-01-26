@extends('user.shop.layout')

@section('title', 'Create Coupon')
@section('page-title', 'Create Coupon')

@section('shop-content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Create New Coupon</h1>
            <p class="text-gray-600">Create a discount coupon code for your customers</p>
        </div>
        <a href="{{ route('user.shop.coupons.index') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Coupons
        </a>
    </div>
</div>

<form action="{{ route('user.shop.coupons.store') }}" method="POST">
    @csrf
    
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
                            <input type="text" name="code" id="code" value="{{ old('code') }}" required
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
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <input type="text" name="description" id="description" value="{{ old('description') }}"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="e.g., Summer sale discount">
                    </div>
                </div>
            </div>

            <!-- Discount Settings -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Discount Settings</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="discount_type" class="block text-sm font-medium text-gray-700 mb-1">Discount Type *</label>
                        <select name="discount_type" id="discount_type" required
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                            <option value="percentage" {{ old('discount_type', 'percentage') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Fixed Amount (Rs.)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-1">Discount Value *</label>
                        <input type="number" name="discount_value" id="discount_value" value="{{ old('discount_value') }}" required step="0.01" min="0"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('discount_value') border-red-500 @enderror">
                        @error('discount_value')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="min_order_amount" class="block text-sm font-medium text-gray-700 mb-1">Minimum Order Amount (Rs.)</label>
                        <input type="number" name="min_order_amount" id="min_order_amount" value="{{ old('min_order_amount') }}" step="0.01" min="0"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="No minimum">
                    </div>
                    
                    <div id="max-discount-field">
                        <label for="max_discount_amount" class="block text-sm font-medium text-gray-700 mb-1">Maximum Discount (Rs.)</label>
                        <input type="number" name="max_discount_amount" id="max_discount_amount" value="{{ old('max_discount_amount') }}" step="0.01" min="0"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="No maximum">
                        <p class="text-xs text-gray-500 mt-1">For percentage discounts only</p>
                    </div>
                </div>
            </div>

            <!-- Usage Limits -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Usage Limits</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="usage_limit" class="block text-sm font-medium text-gray-700 mb-1">Total Usage Limit</label>
                        <input type="number" name="usage_limit" id="usage_limit" value="{{ old('usage_limit') }}" min="1"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="Unlimited">
                        <p class="text-xs text-gray-500 mt-1">Leave empty for unlimited usage</p>
                    </div>
                    
                    <div>
                        <label for="usage_limit_per_customer" class="block text-sm font-medium text-gray-700 mb-1">Usage Limit Per Customer</label>
                        <input type="number" name="usage_limit_per_customer" id="usage_limit_per_customer" value="{{ old('usage_limit_per_customer', 1) }}" min="1"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                        <p class="text-xs text-gray-500 mt-1">How many times each customer can use</p>
                    </div>
                </div>
            </div>

            <!-- Validity Period -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Validity Period</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at') }}"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                        <p class="text-xs text-gray-500 mt-1">Leave empty to start immediately</p>
                    </div>
                    
                    <div>
                        <label for="ends_at" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at') }}"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                        <p class="text-xs text-gray-500 mt-1">Leave empty for no expiry</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Status</h3>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="rounded text-pink-500 focus:ring-pink-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                </div>
            </div>

            <!-- Preview -->
            <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg shadow p-6 text-white">
                <h4 class="font-semibold mb-4">Coupon Preview</h4>
                <div class="bg-white rounded-lg p-4 text-gray-800">
                    <div class="text-center">
                        <p class="text-xs text-gray-500 mb-1">USE CODE</p>
                        <p id="preview-code" class="text-2xl font-bold font-mono tracking-wider">CODE</p>
                        <p id="preview-discount" class="text-lg text-pink-600 font-semibold mt-2">0% OFF</p>
                        <p id="preview-conditions" class="text-xs text-gray-500 mt-2"></p>
                    </div>
                </div>
            </div>

            <!-- Quick Tips -->
            <div class="bg-blue-50 rounded-lg p-4">
                <h4 class="font-semibold text-blue-800 mb-2"><i class="fas fa-lightbulb mr-1"></i> Tips</h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Use short, memorable codes</li>
                    <li>• Set expiry dates to create urgency</li>
                    <li>• Limit usage to prevent abuse</li>
                    <li>• Track usage to measure effectiveness</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-6 flex justify-end space-x-4">
        <a href="{{ route('user.shop.coupons.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" class="px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
            <i class="fas fa-save mr-2"></i> Create Coupon
        </button>
    </div>
</form>

@push('scripts')
<script>
    function generateCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let code = '';
        for (let i = 0; i < 8; i++) {
            code += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('code').value = code;
        updatePreview();
    }

    function updatePreview() {
        const code = document.getElementById('code').value || 'CODE';
        const discountType = document.getElementById('discount_type').value;
        const discountValue = document.getElementById('discount_value').value || 0;
        const minOrder = document.getElementById('min_order_amount').value;

        document.getElementById('preview-code').textContent = code.toUpperCase();
        document.getElementById('preview-discount').textContent = discountType === 'percentage' 
            ? `${discountValue}% OFF` 
            : `Rs. ${discountValue} OFF`;
        
        let conditions = [];
        if (minOrder) conditions.push(`Min. order Rs. ${minOrder}`);
        document.getElementById('preview-conditions').textContent = conditions.join(' • ');
    }

    function toggleMaxDiscount() {
        const discountType = document.getElementById('discount_type').value;
        const maxDiscountField = document.getElementById('max-discount-field');
        maxDiscountField.style.display = discountType === 'percentage' ? 'block' : 'none';
    }

    // Initialize
    toggleMaxDiscount();
    updatePreview();

    // Event listeners
    document.getElementById('code').addEventListener('input', updatePreview);
    document.getElementById('discount_type').addEventListener('change', () => {
        toggleMaxDiscount();
        updatePreview();
    });
    document.getElementById('discount_value').addEventListener('input', updatePreview);
    document.getElementById('min_order_amount').addEventListener('input', updatePreview);
</script>
@endpush
@endsection
