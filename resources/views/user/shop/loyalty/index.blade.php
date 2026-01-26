@extends('user.shop.layout')

@section('title', 'Loyalty Points')
@section('page-title', 'Loyalty Points')

@section('shop-content')
@php
    $loyaltyEnabled = $shop->activeSubscription?->plan?->loyalty_enabled ?? false;
@endphp

@if(!$loyaltyEnabled)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-crown text-yellow-500 text-3xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-yellow-800">Upgrade Required</h3>
                <p class="text-yellow-700">Loyalty points system is available in Professional plan and above.</p>
                <a href="{{ route('user.shop.subscription') }}" class="inline-flex items-center mt-2 text-yellow-800 font-medium hover:underline">
                    Upgrade Now <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
@endif

<div class="{{ !$loyaltyEnabled ? 'opacity-50 pointer-events-none' : '' }}">
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Loyalty Points System</h1>
                <p class="text-gray-600">Reward your customers with points for purchases</p>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="w-12 h-12 mx-auto rounded-full bg-yellow-100 flex items-center justify-center mb-2">
                <i class="fas fa-star text-yellow-500 text-xl"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_points_issued'] ?? 0) }}</p>
            <p class="text-sm text-gray-500">Total Points Issued</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="w-12 h-12 mx-auto rounded-full bg-green-100 flex items-center justify-center mb-2">
                <i class="fas fa-gift text-green-500 text-xl"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_points_redeemed'] ?? 0) }}</p>
            <p class="text-sm text-gray-500">Points Redeemed</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="w-12 h-12 mx-auto rounded-full bg-blue-100 flex items-center justify-center mb-2">
                <i class="fas fa-coins text-blue-500 text-xl"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_active_points'] ?? 0) }}</p>
            <p class="text-sm text-gray-500">Active Points</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="w-12 h-12 mx-auto rounded-full bg-purple-100 flex items-center justify-center mb-2">
                <i class="fas fa-users text-purple-500 text-xl"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['customers_with_points'] ?? 0 }}</p>
            <p class="text-sm text-gray-500">Customers with Points</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Settings -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b">
                <h3 class="font-semibold text-gray-800">Loyalty Settings</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('user.shop.loyalty.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">Enable Loyalty Program</p>
                                <p class="text-sm text-gray-500">Allow customers to earn and redeem points</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_enabled" value="1" {{ ($settings->is_enabled ?? false) ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Points per Rs. 100 spent</label>
                            <input type="number" name="points_per_currency" value="{{ $settings->points_per_currency ?? 1 }}" min="1"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                            <p class="text-xs text-gray-500 mt-1">How many points customer earns per Rs. 100</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Points Value (Rs.)</label>
                            <input type="number" name="currency_per_point" value="{{ $settings->currency_per_point ?? 1 }}" step="0.01" min="0.01"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                            <p class="text-xs text-gray-500 mt-1">Value of 1 point when redeemed (e.g., 1 point = Rs. 1)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Points to Redeem</label>
                            <input type="number" name="min_points_redeem" value="{{ $settings->min_points_redeem ?? 100 }}" min="1"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Points Expiry (Days)</label>
                            <input type="number" name="points_expiry_days" value="{{ $settings->points_expiry_days ?? 365 }}" min="0"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                            <p class="text-xs text-gray-500 mt-1">Set to 0 for no expiry</p>
                        </div>
                    </div>

                    <button type="submit" class="mt-6 w-full py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                        <i class="fas fa-save mr-2"></i> Save Settings
                    </button>
                </form>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Recent Transactions</h3>
                <a href="{{ route('user.shop.loyalty.transactions') }}" class="text-pink-600 text-sm hover:underline">View All</a>
            </div>
            <div class="p-4">
                @if(isset($recentTransactions) && $recentTransactions->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentTransactions as $transaction)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full {{ $transaction->type === 'earn' ? 'bg-green-100' : 'bg-orange-100' }} flex items-center justify-center mr-3">
                                        <i class="fas {{ $transaction->type === 'earn' ? 'fa-plus text-green-500' : 'fa-minus text-orange-500' }}"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-sm">{{ $transaction->customer->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $transaction->description }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold {{ $transaction->type === 'earn' ? 'text-green-600' : 'text-orange-600' }}">
                                        {{ $transaction->type === 'earn' ? '+' : '-' }}{{ $transaction->points }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $transaction->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-history text-4xl mb-3"></i>
                        <p>No transactions yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Top Customers -->
    <div class="mt-6 bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-800">Top Customers by Points</h3>
        </div>
        <div class="p-4">
            @if(isset($topCustomers) && $topCustomers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <th class="pb-3">Customer</th>
                                <th class="pb-3">Total Earned</th>
                                <th class="pb-3">Redeemed</th>
                                <th class="pb-3">Current Balance</th>
                                <th class="pb-3">Expiring Soon</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($topCustomers as $customer)
                                <tr>
                                    <td class="py-3">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center mr-2">
                                                <span class="text-pink-600 font-medium text-sm">{{ substr($customer->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">{{ $customer->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $customer->phone }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-green-600 font-medium">{{ number_format($customer->total_earned ?? 0) }}</td>
                                    <td class="py-3 text-orange-600">{{ number_format($customer->total_redeemed ?? 0) }}</td>
                                    <td class="py-3 font-bold">{{ number_format($customer->loyalty_points ?? 0) }}</td>
                                    <td class="py-3">
                                        @if(($customer->expiring_soon ?? 0) > 0)
                                            <span class="text-red-600 text-sm">{{ $customer->expiring_soon }} pts</span>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-users text-4xl mb-3"></i>
                    <p>No customer loyalty data yet</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
