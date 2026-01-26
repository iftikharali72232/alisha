@extends('layouts.admin')

@section('title', 'Manage Shops')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Shops</h1>
            <p class="text-gray-600">Manage all shops on the platform</p>
        </div>
        <a href="{{ route('admin.shops.create') }}" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Add New Shop
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <form action="{{ route('admin.shops.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Search shops..." 
                    class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <select name="status" class="border rounded-lg px-3 py-2">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div>
                <select name="subscription_status" class="border rounded-lg px-3 py-2">
                    <option value="">All Subscriptions</option>
                    <option value="trial" {{ request('subscription_status') == 'trial' ? 'selected' : '' }}>Trial</option>
                    <option value="active" {{ request('subscription_status') == 'active' ? 'selected' : '' }}>Subscribed</option>
                    <option value="expired" {{ request('subscription_status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                <i class="fas fa-search mr-1"></i> Search
            </button>
        </form>
    </div>

    <!-- Shops Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Shop</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Owner</th>
                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Products</th>
                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Orders</th>
                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Subscription</th>
                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Status</th>
                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($shops as $shop)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            @if($shop->logo)
                                <img src="{{ Storage::url($shop->logo) }}" alt="{{ $shop->name }}" class="w-10 h-10 rounded-lg object-cover mr-3">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-pink-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-store text-pink-500"></i>
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('admin.shops.show', $shop) }}" class="font-medium text-gray-800 hover:text-pink-600">
                                    {{ $shop->name }}
                                </a>
                                <div class="text-xs text-gray-500">{{ $shop->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div>{{ $shop->user->name ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $shop->user->email ?? '' }}</div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="font-medium">{{ $shop->products_count }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="font-medium">{{ $shop->orders_count }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($shop->activeSubscription)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $shop->activeSubscription->status_badge }}">
                                @if($shop->activeSubscription->status === 'trial')
                                    <i class="fas fa-clock mr-1"></i> Trial
                                @else
                                    <i class="fas fa-crown mr-1 text-yellow-500"></i> {{ $shop->activeSubscription->plan->name ?? 'Active' }}
                                @endif
                            </span>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $shop->activeSubscription->daysRemaining() }} days left
                            </div>
                        @else
                            <span class="text-gray-400 text-sm">No subscription</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $shop->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $shop->status === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $shop->status === 'suspended' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($shop->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('admin.shops.show', $shop) }}" class="text-blue-600 hover:text-blue-800" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.shops.edit', $shop) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.shops.subscription', $shop) }}" class="text-purple-600 hover:text-purple-800" title="Subscription">
                                <i class="fas fa-crown"></i>
                            </a>
                            <form action="{{ route('admin.shops.toggle-status', $shop) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="{{ $shop->status === 'active' ? 'text-gray-600 hover:text-gray-800' : 'text-green-600 hover:text-green-800' }}" title="{{ $shop->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas {{ $shop->status === 'active' ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                        <i class="fas fa-store text-4xl mb-4 opacity-50"></i>
                        <p>No shops found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $shops->links() }}
    </div>
</div>
@endsection
