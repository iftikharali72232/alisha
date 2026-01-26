@extends('user.shop.layout')

@section('title', 'Offer Details - ' . $shop->name)

@section('shop-content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Offer Details</h1>
            <p class="text-gray-600">{{ $offer->name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('user.shop.offers.edit', $offer) }}"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('user.shop.offers.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to Offers
            </a>
        </div>
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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Offer Information</h3>

            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Offer Name</label>
                        <p class="text-gray-900">{{ $offer->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $offer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $offer->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                @if($offer->description)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <p class="text-gray-900">{{ $offer->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Discount Details -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Discount Details</h3>

            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type</label>
                        <p class="text-gray-900">
                            {{ $offer->type === 'percentage' ? 'Percentage (%)' : 'Fixed Amount' }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Discount Value</label>
                        <p class="text-gray-900">
                            {{ $offer->type === 'percentage' ? $offer->value . '%' : '$' . number_format($offer->value, 2) }}
                        </p>
                    </div>
                </div>

                @if($offer->min_order_amount)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Order Amount</label>
                        <p class="text-gray-900">${{ number_format($offer->min_order_amount, 2) }}</p>
                    </div>
                @endif

                @if($offer->type === 'percentage' && $offer->max_discount_amount)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Discount Amount</label>
                        <p class="text-gray-900">${{ number_format($offer->max_discount_amount, 2) }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Applicable Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Applicable Products</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Applies To</label>
                    <p class="text-gray-900">
                        @switch($offer->applies_to)
                            @case('all')
                                All Products
                                @break
                            @case('categories')
                                Specific Categories
                                @break
                            @case('products')
                                Specific Products
                                @break
                        @endswitch
                    </p>
                </div>

                @if($offer->applies_to === 'categories' && $offer->applicable_ids)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Selected Categories</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($offer->applicable_ids as $categoryId)
                                @php $category = $shop->categories->find($categoryId) @endphp
                                @if($category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $category->name }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($offer->applies_to === 'products' && $offer->applicable_ids)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Selected Products</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($offer->applicable_ids as $productId)
                                @php $product = $shop->products->find($productId) @endphp
                                @if($product)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $product->name }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Validity Period -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Validity Period</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <p class="text-gray-900">{{ $offer->starts_at ? $offer->starts_at->format('M d, Y H:i') : 'Not set' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <p class="text-gray-900">{{ $offer->ends_at ? $offer->ends_at->format('M d, Y H:i') : 'Not set' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Status</label>
                    @php
                        $now = now();
                        $isValid = $offer->is_active &&
                                  ($offer->starts_at ? $now->gte($offer->starts_at) : true) &&
                                  ($offer->ends_at ? $now->lte($offer->ends_at) : true);
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $isValid ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $isValid ? 'Valid' : 'Not Valid' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Usage Statistics</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Usage</label>
                    <p class="text-2xl font-bold text-gray-900">{{ $offer->usage_count ?? 0 }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Discount Given</label>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($offer->total_discount ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>

            <div class="flex flex-col gap-3">
                <a href="{{ route('user.shop.offers.edit', $offer) }}"
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition text-center">
                    <i class="fas fa-edit mr-2"></i>Edit Offer
                </a>

                <form action="{{ route('user.shop.offers.destroy', $offer) }}" method="POST" class="inline"
                    onsubmit="return confirm('Are you sure you want to delete this offer?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition">
                        <i class="fas fa-trash mr-2"></i>Delete Offer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection