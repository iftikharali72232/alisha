@extends('shops.layout')

@section('title', 'My Account - ' . $shop->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">My Account</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar -->
        <aside>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-pink-100 rounded-full mx-auto flex items-center justify-center mb-4">
                        <span class="text-3xl font-bold text-pink-600">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                    </div>
                    <h3 class="font-semibold text-gray-800">{{ $customer->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $customer->email }}</p>
                </div>

                <!-- Loyalty Points -->
                @if($shop->loyaltySettings?->is_enabled)
                    <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm opacity-90">Loyalty Points</p>
                                <p class="text-2xl font-bold">{{ number_format($customer->loyalty_points) }}</p>
                            </div>
                            <i class="fas fa-star text-3xl opacity-50"></i>
                        </div>
                        @if($customer->loyalty_points >= ($shop->loyaltySettings->minimum_points_redemption ?? 100))
                            <p class="text-xs mt-2 opacity-90">
                                Worth {{ $shop->currency }} {{ number_format($customer->loyalty_points * ($shop->loyaltySettings->points_value ?? 0.01), 2) }}
                            </p>
                        @endif
                    </div>
                @endif

                <!-- Quick Stats -->
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Orders</span>
                        <span class="font-medium">{{ $stats['total_orders'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Spent</span>
                        <span class="font-medium">{{ $shop->currency }} {{ number_format($stats['total_spent'] ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Member Since</span>
                        <span class="font-medium">{{ $customer->created_at->format('M Y') }}</span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="mt-6 pt-6 border-t space-y-2">
                    <a href="{{ route('shop.account', $shop->slug) }}" 
                        class="flex items-center px-3 py-2 rounded-lg text-pink-600 bg-pink-50">
                        <i class="fas fa-user w-5"></i>
                        <span class="ml-3">Profile</span>
                    </a>
                    <a href="{{ route('shop.orders', $shop->slug) }}" 
                        class="flex items-center px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-shopping-bag w-5"></i>
                        <span class="ml-3">Orders</span>
                    </a>
                    <form action="{{ route('shop.logout', $shop->slug) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span class="ml-3">Logout</span>
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Profile Information</h3>
                
                <form action="{{ route('shop.account', $shop->slug) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    @if(session('success'))
                        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $customer->name) }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-pink-500 focus:border-pink-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $customer->email) }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-pink-500 focus:border-pink-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-pink-500 focus:border-pink-500">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit"
                            class="px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Addresses -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Saved Addresses</h3>
                    <button onclick="toggleAddressForm()" class="text-pink-600 hover:text-pink-700 text-sm">
                        <i class="fas fa-plus mr-1"></i>Add New
                    </button>
                </div>

                @if($customer->addresses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($customer->addresses as $address)
                            <div class="border rounded-lg p-4 {{ $address->is_default ? 'border-pink-300 bg-pink-50' : '' }}">
                                <div class="flex items-start justify-between">
                                    <div>
                                        @if($address->is_default)
                                            <span class="text-xs bg-pink-600 text-white px-2 py-0.5 rounded-full mb-2 inline-block">Default</span>
                                        @endif
                                        <p class="font-medium text-gray-800">{{ $address->name ?? $customer->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $address->phone ?? $customer->phone }}</p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $address->address }},
                                            {{ $address->city }}, {{ $address->state }}
                                            {{ $address->postal_code }}, {{ $address->country }}
                                        </p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button class="text-gray-400 hover:text-blue-600"><i class="fas fa-edit"></i></button>
                                        <button class="text-gray-400 hover:text-red-600"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No saved addresses yet</p>
                @endif

                <!-- Add Address Form (Hidden by default) -->
                <div id="addressForm" class="hidden mt-6 pt-6 border-t">
                    <form action="#" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <input type="text" name="address" required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-pink-500 focus:border-pink-500"
                                    placeholder="Street address">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" name="city" required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                                <input type="text" name="state"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                                <input type="text" name="postal_code"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                        </div>
                        <div class="mt-4 flex gap-3">
                            <button type="submit"
                                class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                                Save Address
                            </button>
                            <button type="button" onclick="toggleAddressForm()"
                                class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
                    <a href="{{ route('shop.orders', $shop->slug) }}" class="text-pink-600 hover:text-pink-700 text-sm">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                @if($customer->orders->count() > 0)
                    <div class="space-y-4">
                        @foreach($customer->orders as $order)
                            <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $order->order_number }}</p>
                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium">{{ $shop->currency }} {{ number_format($order->total) }}</p>
                                    <span class="text-xs px-2 py-1 rounded-full 
                                        {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-700' : '' }}
                                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No orders yet</p>
                @endif
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Change Password</h3>
                
                <form action="{{ route('shop.account', $shop->slug) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="password">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                            <input type="password" name="current_password" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-pink-500 focus:border-pink-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-pink-500 focus:border-pink-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-pink-500 focus:border-pink-500">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit"
                            class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleAddressForm() {
    document.getElementById('addressForm').classList.toggle('hidden');
}
</script>
@endsection
