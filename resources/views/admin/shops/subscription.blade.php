@extends('layouts.admin')

@section('title', 'Manage Subscription: ' . $shop->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.shops.show', $shop) }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manage Subscription</h1>
            <p class="text-gray-600">{{ $shop->name }}</p>
        </div>
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

    <div class="grid grid-cols-3 gap-6">
        <!-- Current Subscription -->
        <div class="col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Current Subscription</h3>
                
                @if($shop->activeSubscription)
                    <div class="text-center mb-4">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-medium {{ $shop->activeSubscription->status_badge }}">
                            @if($shop->activeSubscription->isOnTrial())
                                <i class="fas fa-clock mr-2"></i> Trial Period
                            @else
                                <i class="fas fa-crown mr-2 text-yellow-500"></i> {{ $shop->activeSubscription->plan->name ?? 'Active' }}
                            @endif
                        </span>
                    </div>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Plan:</span>
                            <span class="font-medium">{{ $shop->activeSubscription->plan->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="font-medium">{{ ucfirst($shop->activeSubscription->status) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Days Remaining:</span>
                            <span class="font-medium">{{ $shop->activeSubscription->daysRemaining() }}</span>
                        </div>
                        @if($shop->activeSubscription->isOnTrial())
                            <div class="flex justify-between">
                                <span class="text-gray-600">Trial Ends:</span>
                                <span class="font-medium">{{ $shop->activeSubscription->trial_ends_at?->format('M d, Y') }}</span>
                            </div>
                        @else
                            <div class="flex justify-between">
                                <span class="text-gray-600">Expires:</span>
                                <span class="font-medium">{{ $shop->activeSubscription->ends_at?->format('M d, Y') }}</span>
                            </div>
                        @endif
                        @if($shop->activeSubscription->amount_paid > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Amount Paid:</span>
                                <span class="font-medium">Rs. {{ number_format($shop->activeSubscription->amount_paid) }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                        <p>No active subscription</p>
                    </div>
                @endif
            </div>

            <!-- Subscription History -->
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h3 class="font-semibold text-gray-800 mb-4">Subscription History</h3>
                
                @if($shop->subscriptions->count() > 0)
                    <div class="space-y-3">
                        @foreach($shop->subscriptions as $subscription)
                            <div class="p-3 bg-gray-50 rounded-lg text-sm">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-medium">{{ $subscription->plan->name ?? 'Unknown' }}</span>
                                    <span class="px-2 py-1 rounded text-xs {{ $subscription->status_badge }}">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </div>
                                <div class="text-gray-500 text-xs">
                                    {{ $subscription->created_at->format('M d, Y') }}
                                    @if($subscription->amount_paid > 0)
                                        - Rs. {{ number_format($subscription->amount_paid) }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-400 text-center">No history</p>
                @endif
            </div>
        </div>

        <!-- Manage Subscription -->
        <div class="col-span-2">
            <form action="{{ route('admin.shops.subscription.update', $shop) }}" method="POST">
                @csrf
                
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Update Subscription</h3>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select Plan</label>
                            <select name="plan_id" class="w-full border rounded-lg px-3 py-2" required>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" 
                                        {{ $shop->activeSubscription?->plan_id == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} - {{ $plan->price_display }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                            <select name="action" class="w-full border rounded-lg px-3 py-2" required>
                                <option value="start_trial">Start/Restart Trial</option>
                                <option value="activate">Activate Subscription</option>
                                <option value="extend">Extend Current</option>
                                <option value="cancel">Cancel Subscription</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Days</label>
                            <input type="number" name="days" value="30" min="1" max="365"
                                class="w-full border rounded-lg px-3 py-2">
                            <p class="text-xs text-gray-500 mt-1">Trial or subscription duration</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Amount Paid (if activating)</label>
                            <input type="number" name="amount_paid" step="0.01" min="0"
                                class="w-full border rounded-lg px-3 py-2">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                            <select name="payment_method" class="w-full border rounded-lg px-3 py-2">
                                <option value="">-- Select --</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cash">Cash</option>
                                <option value="easypaisa">EasyPaisa</option>
                                <option value="jazzcash">JazzCash</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="free">Free/Promotional</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Reference</label>
                            <input type="text" name="payment_reference" 
                                class="w-full border rounded-lg px-3 py-2" placeholder="Transaction ID or reference">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full border rounded-lg px-3 py-2" 
                            placeholder="Internal notes..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700">
                        <i class="fas fa-save mr-2"></i> Update Subscription
                    </button>
                </div>
            </form>

            <!-- Available Plans Reference -->
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h3 class="font-semibold text-gray-800 mb-4">Available Plans</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    @foreach($plans as $plan)
                        <div class="border rounded-lg p-4 {{ $plan->is_featured ? 'border-pink-500 bg-pink-50' : '' }}">
                            @if($plan->is_featured)
                                <span class="bg-pink-500 text-white text-xs px-2 py-1 rounded-full float-right">Popular</span>
                            @endif
                            <h4 class="font-semibold text-gray-800">{{ $plan->name }}</h4>
                            <p class="text-2xl font-bold text-pink-600 my-2">{{ $plan->price_display }}</p>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($plan->description, 100) }}</p>
                            <ul class="text-xs text-gray-500 space-y-1">
                                <li><i class="fas fa-check text-green-500 mr-1"></i> {{ $plan->max_products ?? 'Unlimited' }} products</li>
                                <li><i class="fas fa-check text-green-500 mr-1"></i> {{ $plan->max_categories ?? 'Unlimited' }} categories</li>
                                @if($plan->loyalty_enabled)
                                    <li><i class="fas fa-check text-green-500 mr-1"></i> Loyalty Points</li>
                                @endif
                                @if($plan->advanced_analytics)
                                    <li><i class="fas fa-check text-green-500 mr-1"></i> Advanced Analytics</li>
                                @endif
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
