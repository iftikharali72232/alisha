@extends('layouts.admin')

@section('title', 'Subscription Plans')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Subscription Plans</h1>
            <p class="text-gray-600">Manage subscription plans for shops</p>
        </div>
        <a href="{{ route('admin.shop-plans.create') }}" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Add New Plan
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Plans Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Plan</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Price</th>
                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Billing</th>
                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Trial</th>
                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Features</th>
                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Subscriptions</th>
                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Status</th>
                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($plans as $plan)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div>
                            <div class="font-medium text-gray-800">{{ $plan->name }}</div>
                            <div class="text-xs text-gray-500">{{ $plan->slug }}</div>
                            @if($plan->description)
                                <div class="text-xs text-gray-400 mt-1">{{ Str::limit($plan->description, 50) }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $plan->price_display }}</div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-sm">{{ ucfirst($plan->billing_cycle) }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-sm">{{ $plan->trial_days }} days</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex flex-wrap justify-center gap-1">
                            @if($plan->loyalty_enabled)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">Loyalty</span>
                            @endif
                            @if($plan->advanced_analytics)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-800">Analytics</span>
                            @endif
                            @if($plan->custom_domain)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">Domain</span>
                            @endif
                            @if($plan->has_variations)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">Variations</span>
                            @endif
                            @if($plan->has_offers)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-indigo-100 text-indigo-800">Offers</span>
                            @endif
                            @if($plan->has_coupons)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-pink-100 text-pink-800">Coupons</span>
                            @endif
                            @if($plan->has_reviews)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-teal-100 text-teal-800">Reviews</span>
                            @endif
                            @if($plan->has_priority_support)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">Priority Support</span>
                            @endif
                            @if($plan->hasUnlimitedProducts())
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">Unlimited Products</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">{{ $plan->max_products }} Products</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="font-medium">{{ $plan->subscriptions_count }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @if($plan->is_featured)
                            <div class="text-xs text-yellow-600 mt-1">
                                <i class="fas fa-star"></i> Featured
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('admin.shop-plans.edit', $plan) }}" class="text-blue-600 hover:text-blue-800" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.shop-plans.toggle-status', $plan) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="{{ $plan->is_active ? 'text-gray-600 hover:text-gray-800' : 'text-green-600 hover:text-green-800' }}" title="{{ $plan->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas {{ $plan->is_active ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                </button>
                            </form>
                            @if($plan->subscriptions_count == 0)
                                <form action="{{ route('admin.shop-plans.destroy', $plan) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                        <i class="fas fa-crown text-4xl mb-4 opacity-50"></i>
                        <p>No subscription plans found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection