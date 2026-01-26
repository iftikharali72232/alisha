@extends('layouts.admin')

@section('title', 'Create Subscription Plan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.shop-plans.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Create Subscription Plan</h1>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.shop-plans.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Plan Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500" required>
            </div>

            <!-- Slug -->
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500">
            </div>

            <!-- Price -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                <input type="number" name="price" id="price" step="0.01" value="{{ old('price') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500" required>
            </div>

            <!-- Billing Cycle -->
            <div>
                <label for="billing_cycle" class="block text-sm font-medium text-gray-700 mb-2">Billing Cycle *</label>
                <select name="billing_cycle" id="billing_cycle" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                    <option value="monthly" {{ old('billing_cycle', 'monthly') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="quarterly" {{ old('billing_cycle') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                    <option value="yearly" {{ old('billing_cycle') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
            </div>

            <!-- Trial Days -->
            <div>
                <label for="trial_days" class="block text-sm font-medium text-gray-700 mb-2">Trial Days *</label>
                <input type="number" name="trial_days" id="trial_days" value="{{ old('trial_days', 30) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500" required>
            </div>

            <!-- Commission Percentage -->
            <div>
                <label for="commission_percentage" class="block text-sm font-medium text-gray-700 mb-2">Commission % *</label>
                <input type="number" name="commission_percentage" id="commission_percentage" step="0.01" value="{{ old('commission_percentage', 10) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500" required>
            </div>

            <!-- Max Products -->
            <div>
                <label for="max_products" class="block text-sm font-medium text-gray-700 mb-2">Max Products *</label>
                <input type="number" name="max_products" id="max_products" value="{{ old('max_products') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                <p class="text-xs text-gray-500 mt-1">Set to -1 for unlimited</p>
            </div>

            <!-- Max Categories -->
            <div>
                <label for="max_categories" class="block text-sm font-medium text-gray-700 mb-2">Max Categories *</label>
                <input type="number" name="max_categories" id="max_categories" value="{{ old('max_categories') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                <p class="text-xs text-gray-500 mt-1">Set to -1 for unlimited</p>
            </div>

            <!-- Max Images per Product -->
            <div>
                <label for="max_images_per_product" class="block text-sm font-medium text-gray-700 mb-2">Max Images per Product *</label>
                <input type="number" name="max_images_per_product" id="max_images_per_product" value="{{ old('max_images_per_product', 5) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500" required>
            </div>

            <!-- Order -->
            <div>
                <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Display Order *</label>
                <input type="number" name="order" id="order" value="{{ old('order', 0) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500" required>
            </div>
        </div>

        <!-- Description -->
        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500">{{ old('description') }}</textarea>
        </div>

        <!-- Features -->
        <div class="mt-6">
            <label for="features_text" class="block text-sm font-medium text-gray-700 mb-2">Features (one per line)</label>
            <textarea name="features_text" id="features_text" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500" placeholder="Enter each feature on a new line">{{ old('features_text') }}</textarea>
        </div>

        <!-- Features Checkboxes -->
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Plan Features</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <label class="flex items-center">
                    <input type="checkbox" name="has_variations" value="1" {{ old('has_variations') ? 'checked' : '' }} class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                    <span class="ml-2 text-sm text-gray-700">Product Variations</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="has_offers" value="1" {{ old('has_offers') ? 'checked' : '' }} class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                    <span class="ml-2 text-sm text-gray-700">Offers</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="has_coupons" value="1" {{ old('has_coupons') ? 'checked' : '' }} class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                    <span class="ml-2 text-sm text-gray-700">Coupons</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="has_loyalty" value="1" {{ old('has_loyalty') ? 'checked' : '' }} class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                    <span class="ml-2 text-sm text-gray-700">Loyalty Program</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="has_reviews" value="1" {{ old('has_reviews') ? 'checked' : '' }} class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                    <span class="ml-2 text-sm text-gray-700">Reviews</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="has_custom_domain" value="1" {{ old('has_custom_domain') ? 'checked' : '' }} class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                    <span class="ml-2 text-sm text-gray-700">Custom Domain</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="has_analytics" value="1" {{ old('has_analytics') ? 'checked' : '' }} class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                    <span class="ml-2 text-sm text-gray-700">Analytics</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="has_priority_support" value="1" {{ old('has_priority_support') ? 'checked' : '' }} class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                    <span class="ml-2 text-sm text-gray-700">Priority Support</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                    <span class="ml-2 text-sm text-gray-700">Featured Plan</span>
                </label>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="mt-6 flex justify-end space-x-4">
            <a href="{{ route('admin.shop-plans.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded-md hover:bg-pink-700">Create Plan</button>
        </div>
    </form>
</div>
@endsection