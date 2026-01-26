@extends('user.shop.layout')

@section('title', 'Edit Customer - ' . $shop->name)

@section('shop-content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Customer</h1>
            <p class="text-gray-600">Update customer information</p>
        </div>
        <a href="{{ route('user.shop.customers.index') }}"
            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Customers
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

<form action="{{ route('user.shop.customers.update', $customer) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $customer->first_name) }}" required
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('first_name') border-red-500 @enderror">
                            @error('first_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $customer->last_name) }}" required
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('last_name') border-red-500 @enderror">
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" required
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                        </div>

                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $customer->date_of_birth ? $customer->date_of_birth->format('Y-m-d') : '') }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Settings -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Settings</h3>

                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $customer->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 text-sm text-gray-700">Active Account</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="email_verified" id="email_verified" value="1" {{ old('email_verified', $customer->email_verified) ? 'checked' : '' }}>
                        <label for="email_verified" class="ml-2 text-sm text-gray-700">Email Verified</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="marketing_emails" id="marketing_emails" value="1" {{ old('marketing_emails', $customer->marketing_emails) ? 'checked' : '' }}>
                        <label for="marketing_emails" class="ml-2 text-sm text-gray-700">Subscribe to Marketing Emails</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Statistics</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Orders</label>
                        <p class="text-2xl font-bold text-gray-900">{{ $customer->orders_count ?? 0 }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Spent</label>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($customer->total_spent ?? 0, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Order</label>
                        <p class="text-gray-900">{{ $customer->last_order_date ? \Carbon\Carbon::parse($customer->last_order_date)->format('M d, Y') : 'Never' }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full bg-pink-500 text-white py-2 px-4 rounded-lg hover:bg-pink-600 transition">
                        <i class="fas fa-save mr-2"></i>Update Customer
                    </button>
                    <a href="{{ route('user.shop.customers.index') }}" class="w-full bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition text-center">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection