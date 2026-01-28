@extends('user.shop.layout')

@section('title', 'Loyalty Transactions')
@section('page-title', 'Loyalty Transactions')

@section('shop-content')
@php
    $loyaltyEnabled = $shop->activeSubscription?->plan?->loyalty_enabled ?? false;
@endphp

@if(!$loyaltyEnabled)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
            <div>
                <h3 class="text-yellow-800 font-medium">Loyalty Program Not Available</h3>
                <p class="text-yellow-700">Loyalty points system is available in Professional plan and above.</p>
            </div>
        </div>
    </div>
@else
<div class="{{ !$loyaltyEnabled ? 'opacity-50 pointer-events-none' : '' }}">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Loyalty Transactions</h1>
            <p class="text-gray-600">View all loyalty points transactions</p>
        </div>
        <a href="{{ route('user.shop.loyalty.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i> Back to Loyalty
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Type</label>
                <select name="type" class="border rounded-lg px-3 py-2">
                    <option value="">All Types</option>
                    <option value="earned" {{ request('type') == 'earned' ? 'selected' : '' }}>Earned</option>
                    <option value="redeemed" {{ request('type') == 'redeemed' ? 'selected' : '' }}>Redeemed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                <select name="customer_id" class="border rounded-lg px-3 py-2">
                    <option value="">All Customers</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                @if(request()->hasAny(['type', 'customer_id']))
                    <a href="{{ route('user.shop.loyalty.transactions') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 ml-2">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-800">All Transactions ({{ $transactions->total() }})</h3>
        </div>

        @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Points</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-gray-500"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $transaction->customer->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $transaction->customer->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $transaction->type === 'earned' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-bold {{ $transaction->type === 'earned' ? 'text-green-600' : 'text-orange-600' }}">
                                        {{ $transaction->type === 'earned' ? '+' : '-' }}{{ number_format($transaction->points) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $transaction->description }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->created_at->format('M d, Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-history text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No transactions found</h3>
                <p class="text-gray-500">
                    @if(request()->hasAny(['type', 'customer_id']))
                        No transactions match your current filters.
                    @else
                        No loyalty transactions have been recorded yet.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
@endif

@endsection