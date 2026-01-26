@extends('user.shop.layout')

@section('title', 'Edit Offer - ' . $shop->name)

@section('shop-content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Offer</h1>
            <p class="text-gray-600">Update offer information</p>
        </div>
        <a href="{{ route('user.shop.offers.index') }}"
            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Offers
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

<form action="{{ route('user.shop.offers.update', $offer) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Offer Details</h3>

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Offer Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $offer->name) }}" required
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('name') border-red-500 @enderror"
                            placeholder="e.g., Summer Sale">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="Describe your offer...">{{ old('description', $offer->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Discount -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Discount Settings</h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Discount Type *</label>
                            <select name="type" id="type" required
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('type') border-red-500 @enderror">
                                <option value="percentage" {{ old('type', $offer->type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ old('type', $offer->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="value" class="block text-sm font-medium text-gray-700 mb-1">Discount Value *</label>
                            <input type="number" name="value" id="value" step="0.01" min="0" value="{{ old('value', $offer->value) }}" required
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('value') border-red-500 @enderror">
                            @error('value')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="min_order_amount" class="block text-sm font-medium text-gray-700 mb-1">Minimum Order Amount</label>
                            <input type="number" name="min_order_amount" id="min_order_amount" step="0.01" min="0" value="{{ old('min_order_amount', $offer->min_order_amount) }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                                placeholder="0.00">
                        </div>

                        <div id="maxDiscountField" class="{{ $offer->type != 'percentage' ? 'hidden' : '' }}">
                            <label for="max_discount_amount" class="block text-sm font-medium text-gray-700 mb-1">Max Discount Amount</label>
                            <input type="number" name="max_discount_amount" id="max_discount_amount" step="0.01" min="0" value="{{ old('max_discount_amount', $offer->max_discount_amount) }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                                placeholder="0.00">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Applicable Products</h3>

                <div class="space-y-4">
                    <div>
                        <label for="applies_to" class="block text-sm font-medium text-gray-700 mb-1">Apply To *</label>
                        <select name="applies_to" id="applies_to" required
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('applies_to') border-red-500 @enderror">
                            <option value="all" {{ old('applies_to', $offer->applies_to) == 'all' ? 'selected' : '' }}>All Products</option>
                            <option value="categories" {{ old('applies_to', $offer->applies_to) == 'categories' ? 'selected' : '' }}>Specific Categories</option>
                            <option value="products" {{ old('applies_to', $offer->applies_to) == 'products' ? 'selected' : '' }}>Specific Products</option>
                        </select>
                        @error('applies_to')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="categoriesField" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Categories</label>
                        <div class="max-h-40 overflow-y-auto border rounded-lg p-2">
                            @foreach($shop->categories as $category)
                                <label class="flex items-center space-x-2 py-1">
                                    <input type="checkbox" name="applicable_ids[]" value="{{ $category->id }}"
                                        {{ in_array($category->id, old('applicable_ids', $offer->applicable_ids ?? [])) ? 'checked' : '' }}>
                                    <span class="text-sm">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div id="productsField" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Products</label>
                        <div class="max-h-40 overflow-y-auto border rounded-lg p-2">
                            @foreach($shop->products as $product)
                                <label class="flex items-center space-x-2 py-1">
                                    <input type="checkbox" name="applicable_ids[]" value="{{ $product->id }}"
                                        {{ in_array($product->id, old('applicable_ids', $offer->applicable_ids ?? [])) ? 'checked' : '' }}>
                                    <span class="text-sm">{{ $product->name }}</span>
                                </label>
                            @endforeach
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
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $offer->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                            <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', $offer->starts_at ? $offer->starts_at->format('Y-m-d\TH:i') : '') }}" required
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('starts_at') border-red-500 @enderror">
                            @error('starts_at')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ends_at" class="block text-sm font-medium text-gray-700 mb-1">End Date *</label>
                            <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', $offer->ends_at ? $offer->ends_at->format('Y-m-d\TH:i') : '') }}" required
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('ends_at') border-red-500 @enderror">
                            @error('ends_at')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full bg-pink-500 text-white py-2 px-4 rounded-lg hover:bg-pink-600 transition">
                        <i class="fas fa-save mr-2"></i>Update Offer
                    </button>
                    <a href="{{ route('user.shop.offers.index') }}" class="w-full bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition text-center">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.getElementById('type').addEventListener('change', function() {
    const maxDiscountField = document.getElementById('maxDiscountField');
    maxDiscountField.classList.toggle('hidden', this.value !== 'percentage');
});

document.getElementById('applies_to').addEventListener('change', function() {
    const categoriesField = document.getElementById('categoriesField');
    const productsField = document.getElementById('productsField');

    categoriesField.classList.toggle('hidden', this.value !== 'categories');
    productsField.classList.toggle('hidden', this.value !== 'products');
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('type').dispatchEvent(new Event('change'));
    document.getElementById('applies_to').dispatchEvent(new Event('change'));
});
</script>
@endsection