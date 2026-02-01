@extends('user.shop.layout')

@section('title', 'Orders')
@section('page-title', 'Orders')

@section('shop-content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Orders</h1>
            <p class="text-gray-600">Manage your customer orders</p>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
        <p class="text-sm text-gray-500">All Orders</p>
    </div>
    <div class="bg-yellow-50 rounded-lg shadow p-4 text-center">
        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        <p class="text-sm text-gray-500">Pending</p>
    </div>
    <div class="bg-blue-50 rounded-lg shadow p-4 text-center">
        <p class="text-2xl font-bold text-blue-600">{{ $stats['processing'] }}</p>
        <p class="text-sm text-gray-500">Processing</p>
    </div>
    <div class="bg-purple-50 rounded-lg shadow p-4 text-center">
        <p class="text-2xl font-bold text-purple-600">{{ $stats['shipped'] }}</p>
        <p class="text-sm text-gray-500">Shipped</p>
    </div>
    <div class="bg-green-50 rounded-lg shadow p-4 text-center">
        <p class="text-2xl font-bold text-green-600">{{ $stats['delivered'] }}</p>
        <p class="text-sm text-gray-500">Delivered</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form action="{{ route('user.shop.orders.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order number, customer..." 
                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
        </div>
        <div>
            <select name="status" class="border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div>
            <input type="date" name="date" value="{{ request('date') }}" 
                class="border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
        </div>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-search mr-2"></i> Filter
        </button>
    </form>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-pink-600">{{ $order->order_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $order->customer?->name ?? $order->billing_name ?? 'Guest' }}</div>
                                <div class="text-xs text-gray-500">{{ $order->customer?->phone ?? $order->billing_phone ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">{{ $order->items_count ?? $order->items->count() }} items</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Rs. {{ number_format($order->total) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $order->payment_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $order->payment_status === 'refunded' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select onchange="updateOrderStatus({{ $order->id }}, this.value)" 
                                    class="text-sm border rounded px-2 py-1 focus:ring-pink-500 focus:border-pink-500
                                        {{ $order->status === 'pending' ? 'bg-yellow-50' : '' }}
                                        {{ $order->status === 'processing' ? 'bg-blue-50' : '' }}
                                        {{ $order->status === 'shipped' ? 'bg-purple-50' : '' }}
                                        {{ $order->status === 'delivered' ? 'bg-green-50' : '' }}
                                        {{ $order->status === 'cancelled' ? 'bg-red-50' : '' }}">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $order->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('user.shop.orders.show', $order) }}" class="text-pink-600 hover:text-pink-900">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <i class="fas fa-shopping-cart text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No orders yet</h3>
            <p class="text-gray-500">Orders will appear here when customers place them</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function updateOrderStatus(orderId, status) {
        fetch(`{{ url('user/shop/orders') }}/${orderId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
                alert.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Order status updated';
                document.body.appendChild(alert);
                setTimeout(() => alert.remove(), 3000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
@endpush
@endsection
