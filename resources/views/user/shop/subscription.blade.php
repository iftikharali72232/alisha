@extends('user.shop.layout')

@section('title', 'Subscription')
@section('page-title', 'Subscription')

@section('shop-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Subscription Management</h1>
    <p class="text-gray-600">Manage your shop subscription plan</p>
</div>

<!-- Current Plan -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6">
        @if($shop->activeSubscription)
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="w-16 h-16 rounded-full {{ $shop->activeSubscription->isOnTrial() ? 'bg-blue-100' : 'bg-green-100' }} flex items-center justify-center mr-4">
                        <i class="fas {{ $shop->activeSubscription->isOnTrial() ? 'fa-clock text-blue-500' : 'fa-crown text-green-500' }} text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Current Plan</p>
                        <h2 class="text-2xl font-bold text-gray-800">
                            {{ $shop->activeSubscription->isOnTrial() ? 'Free Trial' : $shop->activeSubscription->plan->name }}
                        </h2>
                        @if($shop->activeSubscription->isOnTrial())
                            <p class="text-blue-600">{{ $shop->activeSubscription->daysRemaining() }} days remaining in trial</p>
                        @else
                            <p class="text-gray-600">
                                Expires: {{ $shop->activeSubscription->ends_at->format('d M Y') }}
                                ({{ $shop->activeSubscription->daysRemaining() }} days left)
                            </p>
                        @endif
                    </div>
                </div>
                
                @if(!$shop->activeSubscription->isOnTrial() && $shop->activeSubscription->plan)
                    <div class="text-right">
                        <p class="text-3xl font-bold text-pink-600">Rs. {{ number_format($shop->activeSubscription->plan->price) }}</p>
                        <p class="text-gray-500">/ {{ $shop->activeSubscription->plan->billing_cycle }}</p>
                    </div>
                @endif
            </div>

            <!-- Plan Features -->
            @if($shop->activeSubscription->plan)
                <div class="mt-6 pt-6 border-t">
                    <h3 class="font-semibold text-gray-800 mb-4">Your Plan Includes:</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg text-center">
                            <p class="text-2xl font-bold text-gray-800">{{ $shop->activeSubscription->plan->max_products == -1 ? '∞' : $shop->activeSubscription->plan->max_products }}</p>
                            <p class="text-sm text-gray-500">Products</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg text-center">
                            <p class="text-2xl font-bold text-gray-800">{{ $shop->activeSubscription->plan->max_categories == -1 ? '∞' : $shop->activeSubscription->plan->max_categories }}</p>
                            <p class="text-sm text-gray-500">Categories</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg text-center">
                            <p class="text-2xl font-bold text-gray-800">{{ $shop->activeSubscription->plan->max_coupons == -1 ? '∞' : $shop->activeSubscription->plan->max_coupons }}</p>
                            <p class="text-sm text-gray-500">Coupons</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg text-center">
                            <p class="text-2xl font-bold text-gray-800">{{ $shop->activeSubscription->plan->max_sliders == -1 ? '∞' : $shop->activeSubscription->plan->max_sliders }}</p>
                            <p class="text-sm text-gray-500">Sliders</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-2">
                        <div class="flex items-center {{ $shop->activeSubscription->plan->loyalty_enabled ? 'text-green-600' : 'text-gray-400' }}">
                            <i class="fas {{ $shop->activeSubscription->plan->loyalty_enabled ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                            <span class="text-sm">Loyalty Points</span>
                        </div>
                        <div class="flex items-center {{ $shop->activeSubscription->plan->advanced_analytics ? 'text-green-600' : 'text-gray-400' }}">
                            <i class="fas {{ $shop->activeSubscription->plan->advanced_analytics ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                            <span class="text-sm">Advanced Analytics</span>
                        </div>
                        <div class="flex items-center {{ $shop->activeSubscription->plan->custom_domain ? 'text-green-600' : 'text-gray-400' }}">
                            <i class="fas {{ $shop->activeSubscription->plan->custom_domain ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                            <span class="text-sm">Custom Domain</span>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-8">
                <div class="w-20 h-20 mx-auto rounded-full bg-red-100 flex items-center justify-center mb-4">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-500"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">No Active Subscription</h2>
                <p class="text-gray-600 mb-4">Your shop features are limited. Choose a plan below to continue.</p>
            </div>
        @endif
    </div>
</div>

<!-- Available Plans -->
<div class="mb-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">
        {{ $shop->activeSubscription ? 'Upgrade Your Plan' : 'Choose a Plan' }}
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($plans as $plan)
            @php
                $isCurrentPlan = $shop->activeSubscription && $shop->activeSubscription->plan_id === $plan->id && !$shop->activeSubscription->isOnTrial();
            @endphp
            <div class="bg-white rounded-lg shadow {{ $plan->is_featured ? 'ring-2 ring-pink-500' : '' }} {{ $isCurrentPlan ? 'opacity-75' : '' }}">
                @if($plan->is_featured)
                    <div class="bg-pink-500 text-white text-center py-1 text-sm font-medium rounded-t-lg">
                        Most Popular
                    </div>
                @endif
                
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800">{{ $plan->name }}</h3>
                    <p class="text-gray-500 text-sm mb-4">{{ $plan->description }}</p>
                    
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-gray-800">Rs. {{ number_format($plan->price) }}</span>
                        <span class="text-gray-500">/ {{ $plan->billing_cycle }}</span>
                    </div>

                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center text-sm">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>{{ $plan->max_products == -1 ? 'Unlimited' : $plan->max_products }} Products</span>
                        </li>
                        <li class="flex items-center text-sm">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>{{ $plan->max_categories == -1 ? 'Unlimited' : $plan->max_categories }} Categories</span>
                        </li>
                        <li class="flex items-center text-sm">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>{{ $plan->max_coupons == -1 ? 'Unlimited' : $plan->max_coupons }} Coupons</span>
                        </li>
                        <li class="flex items-center text-sm">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>{{ $plan->max_gallery_images == -1 ? 'Unlimited' : $plan->max_gallery_images }} Gallery Images</span>
                        </li>
                        <li class="flex items-center text-sm {{ $plan->loyalty_enabled ? '' : 'text-gray-400' }}">
                            <i class="fas {{ $plan->loyalty_enabled ? 'fa-check text-green-500' : 'fa-times text-gray-400' }} mr-2"></i>
                            <span>Loyalty Points</span>
                        </li>
                        <li class="flex items-center text-sm {{ $plan->advanced_analytics ? '' : 'text-gray-400' }}">
                            <i class="fas {{ $plan->advanced_analytics ? 'fa-check text-green-500' : 'fa-times text-gray-400' }} mr-2"></i>
                            <span>Advanced Analytics</span>
                        </li>
                        <li class="flex items-center text-sm {{ $plan->custom_domain ? '' : 'text-gray-400' }}">
                            <i class="fas {{ $plan->custom_domain ? 'fa-check text-green-500' : 'fa-times text-gray-400' }} mr-2"></i>
                            <span>Custom Domain</span>
                        </li>
                    </ul>

                    @if($isCurrentPlan)
                        <button disabled class="w-full py-2 bg-gray-300 text-gray-600 rounded-lg cursor-not-allowed">
                            Current Plan
                        </button>
                    @else
                        <form action="{{ route('user.shop.subscription.request') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <button type="submit" class="w-full py-2 {{ $plan->is_featured ? 'bg-pink-600 hover:bg-pink-700' : 'bg-gray-800 hover:bg-gray-900' }} text-white rounded-lg">
                                {{ $shop->activeSubscription ? 'Upgrade' : 'Subscribe' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Payment Instructions -->
<div class="bg-blue-50 rounded-lg p-6">
    <h3 class="font-semibold text-blue-800 mb-2"><i class="fas fa-info-circle mr-2"></i> Payment Instructions</h3>
    <p class="text-blue-700 text-sm mb-4">
        After selecting a plan, your subscription request will be sent to the admin for approval. 
        You will receive payment instructions via email or WhatsApp.
    </p>
    <div class="text-sm text-blue-600">
        <p><strong>Contact for payment:</strong></p>
        <p><i class="fab fa-whatsapp mr-1"></i> +92 XXX XXXXXXX</p>
        <p><i class="fas fa-envelope mr-1"></i> admin@example.com</p>
    </div>
</div>

<!-- Subscription History -->
@if($subscriptionHistory->count() > 0)
    <div class="mt-6 bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-800">Subscription History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($subscriptionHistory as $sub)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-medium">{{ $sub->plan?->name ?? 'Trial' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($sub->starts_at && $sub->ends_at)
                                    {{ $sub->starts_at->format('d M Y') }} - {{ $sub->ends_at->format('d M Y') }}
                                @else
                                    Pending Approval
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                Rs. {{ number_format($sub->amount_paid ?? 0) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $sub->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $sub->status === 'expired' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $sub->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $sub->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ ucfirst($sub->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
