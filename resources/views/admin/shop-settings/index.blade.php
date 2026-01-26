@extends('layouts.admin')

@section('title', 'Shop Global Settings')
@section('page-title', 'Shop Global Settings')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Shop Global Settings</h1>
    <p class="text-gray-600 mt-1">Manage global settings for all shops including trial, subscription, and feature limits</p>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

<form action="{{ route('admin.shop-settings.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- General Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-cog text-blue-500 mr-2"></i>
                General Settings
            </h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Enable Shop Feature</label>
                        <p class="text-xs text-gray-500">Allow users to create and manage shops</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="shop_enabled" value="1" class="sr-only peer" {{ $config['general']['shop_enabled'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Max Shops Per User</label>
                        <p class="text-xs text-gray-500">Maximum shops a user can create</p>
                    </div>
                    <input type="number" name="max_shops_per_user" value="{{ $config['general']['max_shops_per_user'] }}" min="1" max="10"
                        class="w-20 border rounded-lg px-3 py-1 text-center focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Require Approval</label>
                        <p class="text-xs text-gray-500">Admin must approve new shops</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="require_approval" value="1" class="sr-only peer" {{ $config['general']['require_approval'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Default Currency</label>
                        <p class="text-xs text-gray-500">Default currency for new shops</p>
                    </div>
                    <select name="default_currency" class="border rounded-lg px-3 py-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="PKR" {{ $config['general']['default_currency'] == 'PKR' ? 'selected' : '' }}>PKR (₨)</option>
                        <option value="USD" {{ $config['general']['default_currency'] == 'USD' ? 'selected' : '' }}>USD ($)</option>
                        <option value="EUR" {{ $config['general']['default_currency'] == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                        <option value="GBP" {{ $config['general']['default_currency'] == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                    </select>
                </div>

                <div class="flex items-center justify-between py-2">
                    <div>
                        <label class="text-sm font-medium text-gray-900">WhatsApp Support</label>
                        <p class="text-xs text-gray-500">Show WhatsApp widget on shop pages</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="whatsapp_support" value="1" class="sr-only peer" {{ $config['general']['whatsapp_support'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Trial Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-hourglass-half text-yellow-500 mr-2"></i>
                Trial Settings
            </h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Enable Trial</label>
                        <p class="text-xs text-gray-500">Allow trial period for new shops</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="trial_enabled" value="1" class="sr-only peer" {{ $config['trial']['trial_enabled'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Trial Days</label>
                        <p class="text-xs text-gray-500">Duration of trial period</p>
                    </div>
                    <input type="number" name="trial_days" value="{{ $config['trial']['trial_days'] }}" min="1" max="365"
                        class="w-20 border rounded-lg px-3 py-1 text-center focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Max Products</label>
                        <input type="number" name="trial_max_products" value="{{ $config['trial']['trial_max_products'] }}" min="1"
                            class="w-full mt-1 border rounded-lg px-3 py-1 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-900">Max Categories</label>
                        <input type="number" name="trial_max_categories" value="{{ $config['trial']['trial_max_categories'] }}" min="1"
                            class="w-full mt-1 border rounded-lg px-3 py-1 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 py-2 border-b border-gray-100">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Max Coupons</label>
                        <input type="number" name="trial_max_coupons" value="{{ $config['trial']['trial_max_coupons'] }}" min="0"
                            class="w-full mt-1 border rounded-lg px-3 py-1 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-900">Max Sliders</label>
                        <input type="number" name="trial_max_sliders" value="{{ $config['trial']['trial_max_sliders'] }}" min="0"
                            class="w-full mt-1 border rounded-lg px-3 py-1 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                </div>

                <div class="flex items-center justify-between py-2">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Loyalty in Trial</label>
                        <p class="text-xs text-gray-500">Enable loyalty points</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="trial_loyalty_enabled" value="1" class="sr-only peer" {{ $config['trial']['trial_loyalty_enabled'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Subscription Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-crown text-purple-500 mr-2"></i>
                Subscription Settings
            </h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Enable Subscriptions</label>
                        <p class="text-xs text-gray-500">Allow paid subscriptions</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="subscription_enabled" value="1" class="sr-only peer" {{ $config['subscription']['subscription_enabled'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Auto Suspend Expired</label>
                        <p class="text-xs text-gray-500">Suspend shops after expiry</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="auto_suspend_expired" value="1" class="sr-only peer" {{ $config['subscription']['auto_suspend_expired'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Grace Period (Days)</label>
                        <p class="text-xs text-gray-500">Days after expiry before suspend</p>
                    </div>
                    <input type="number" name="grace_period_days" value="{{ $config['subscription']['grace_period_days'] }}" min="0" max="30"
                        class="w-20 border rounded-lg px-3 py-1 text-center focus:ring-purple-500 focus:border-purple-500">
                </div>

                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Send Expiry Reminder</label>
                        <p class="text-xs text-gray-500">Email reminders before expiry</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="send_expiry_reminder" value="1" class="sr-only peer" {{ $config['subscription']['send_expiry_reminder'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between py-2">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Reminder Days Before</label>
                        <p class="text-xs text-gray-500">Days before expiry to remind</p>
                    </div>
                    <input type="number" name="reminder_days_before" value="{{ $config['subscription']['reminder_days_before'] }}" min="1" max="30"
                        class="w-20 border rounded-lg px-3 py-1 text-center focus:ring-purple-500 focus:border-purple-500">
                </div>
            </div>
        </div>

        <!-- Features Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-puzzle-piece text-green-500 mr-2"></i>
                Features Settings
            </h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Enable Reviews</label>
                        <p class="text-xs text-gray-500">Product reviews feature</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="enable_reviews" value="1" class="sr-only peer" {{ $config['features']['enable_reviews'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Enable Loyalty</label>
                        <p class="text-xs text-gray-500">Loyalty points system</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="enable_loyalty" value="1" class="sr-only peer" {{ $config['features']['enable_loyalty'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Enable Coupons</label>
                        <p class="text-xs text-gray-500">Coupon codes feature</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="enable_coupons" value="1" class="sr-only peer" {{ $config['features']['enable_coupons'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Enable Offers</label>
                        <p class="text-xs text-gray-500">Special offers feature</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="enable_offers" value="1" class="sr-only peer" {{ $config['features']['enable_offers'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Customer Accounts</label>
                        <p class="text-xs text-gray-500">Allow customer registration</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="enable_customer_accounts" value="1" class="sr-only peer" {{ $config['features']['enable_customer_accounts'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between py-2">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Inventory Tracking</label>
                        <p class="text-xs text-gray-500">Track product stock levels</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="enable_inventory_tracking" value="1" class="sr-only peer" {{ $config['features']['enable_inventory_tracking'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div class="mt-6 flex justify-end">
        <button type="submit" class="bg-rose-600 text-white px-8 py-3 rounded-lg hover:bg-rose-700 transition font-medium">
            <i class="fas fa-save mr-2"></i>Save All Settings
        </button>
    </div>
</form>
@endsection
