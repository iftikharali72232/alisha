@extends('user.shop.layout')

@section('title', 'Customer Details - ' . $shop->name)

@section('shop-content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Customer Details</h1>
            <p class="text-gray-600">{{ $customer->first_name }} {{ $customer->last_name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('user.shop.customers.edit', $customer) }}"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('user.shop.customers.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to Customers
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
        <!-- Personal Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h3>

            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <p class="text-gray-900">{{ $customer->first_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <p class="text-gray-900">{{ $customer->last_name }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <p class="text-gray-900">{{ $customer->email }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <p class="text-gray-900">{{ $customer->phone ?: 'Not provided' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                        <p class="text-gray-900">{{ $customer->date_of_birth ? $customer->date_of_birth->format('M d, Y') : 'Not provided' }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Member Since</label>
                    <p class="text-gray-900">{{ $customer->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Account Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Status</h3>

            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $customer->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Verification</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $customer->email_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $customer->email_verified ? 'Verified' : 'Unverified' }}
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Marketing Emails</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $customer->marketing_emails ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $customer->marketing_emails ? 'Subscribed' : 'Not Subscribed' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Orders</h3>

            @if($customer->orders && $customer->orders->count() > 0)
                <div class="space-y-3">
                    @foreach($customer->orders->take(5) as $order)
                        <div class="flex items-center justify-between p-3 border rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Order #{{ $order->id }}</p>
                                <p class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($customer->orders->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('user.shop.orders.index', ['customer' => $customer->id]) }}"
                            class="text-pink-500 hover:text-pink-600 font-medium">
                            View all orders ({{ $customer->orders->count() }})
                        </a>
                    </div>
                @endif
            @else
                <p class="text-gray-500 text-center py-4">No orders yet</p>
            @endif
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Average Order Value</label>
                    <p class="text-2xl font-bold text-gray-900">
                        ${{ $customer->orders_count > 0 ? number_format(($customer->total_spent ?? 0) / $customer->orders_count, 2) : '0.00' }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Order Date</label>
                    <p class="text-gray-900">{{ $customer->last_order_date ? \Carbon\Carbon::parse($customer->last_order_date)->format('M d, Y') : 'Never' }}</p>
                </div>
            </div>
        </div>

        <!-- Loyalty Points -->
        @if(isset($customer->loyalty_points))
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Loyalty Program</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Points</label>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($customer->loyalty_points) }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Points Value</label>
                    <p class="text-gray-900">${{ number_format($customer->loyalty_points * 0.01, 2) }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>

            <div class="flex flex-col gap-3">
                <a href="{{ route('user.shop.customers.edit', $customer) }}"
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition text-center">
                    <i class="fas fa-edit mr-2"></i>Edit Customer
                </a>

                <a href="mailto:{{ $customer->email }}"
                    class="w-full bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition text-center">
                    <i class="fas fa-envelope mr-2"></i>Send Email
                </a>

                @if(Route::has('user.shop.customers.destroy'))
                    <form action="{{ route('user.shop.customers.destroy', $customer) }}" method="POST" class="inline"
                        onsubmit="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition">
                            <i class="fas fa-trash mr-2"></i>Delete Customer
                        </button>
                    </form>
                @else
                    <button type="button" disabled class="w-full bg-red-300 text-white py-2 px-4 rounded-lg cursor-not-allowed" title="Delete route not available on this environment">
                        <i class="fas fa-trash mr-2"></i>Delete Customer
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection