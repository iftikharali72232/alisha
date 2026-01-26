@extends('user.shop.layout')

@section('title', 'Coupons')
@section('page-title', 'Coupons')

@section('shop-content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Coupon Codes</h1>
            <p class="text-gray-600">Create and manage discount coupons</p>
        </div>
        <a href="{{ route('user.shop.coupons.create') }}" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700">
            <i class="fas fa-plus mr-2"></i> Create Coupon
        </a>
    </div>
</div>

<!-- Coupons List -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    @if($coupons->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($coupons as $coupon)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="px-3 py-2 bg-gray-100 rounded font-mono text-sm font-bold">
                                        {{ $coupon->code }}
                                    </div>
                                    <button onclick="copyCode('{{ $coupon->code }}')" class="ml-2 text-gray-400 hover:text-gray-600" title="Copy code">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                                @if($coupon->description)
                                    <p class="text-xs text-gray-500 mt-1">{{ $coupon->description }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-lg font-bold text-green-600">
                                    @if($coupon->discount_type === 'percentage')
                                        {{ $coupon->discount_value }}%
                                    @else
                                        Rs. {{ number_format($coupon->discount_value) }}
                                    @endif
                                </span>
                                @if($coupon->min_order_amount)
                                    <p class="text-xs text-gray-500">Min: Rs. {{ number_format($coupon->min_order_amount) }}</p>
                                @endif
                                @if($coupon->max_discount_amount && $coupon->discount_type === 'percentage')
                                    <p class="text-xs text-gray-500">Max: Rs. {{ number_format($coupon->max_discount_amount) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <span class="font-medium">{{ $coupon->usage_count }}</span>
                                    <span class="text-gray-500">/ {{ $coupon->usage_limit ?? '∞' }}</span>
                                </div>
                                @if($coupon->usage_limit_per_customer)
                                    <p class="text-xs text-gray-500">{{ $coupon->usage_limit_per_customer }} per customer</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($coupon->starts_at && $coupon->ends_at)
                                    <div class="text-sm">
                                        <p class="text-gray-900">{{ $coupon->starts_at->format('d M') }} - {{ $coupon->ends_at->format('d M Y') }}</p>
                                        @if($coupon->isValid())
                                            <p class="text-xs text-green-600">{{ $coupon->ends_at->diffForHumans() }}</p>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">No expiry</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($coupon->isValid())
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                @elseif(!$coupon->is_active)
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Disabled</span>
                                @elseif($coupon->ends_at && $coupon->ends_at->isPast())
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Expired</span>
                                @elseif($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit)
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Limit Reached</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('user.shop.coupons.show', $coupon) }}" class="text-gray-600 hover:text-gray-900 mr-3" title="View Usage">
                                    <i class="fas fa-chart-bar"></i>
                                </a>
                                <a href="{{ route('user.shop.coupons.edit', $coupon) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('user.shop.coupons.destroy', $coupon) }}" method="POST" class="inline" onsubmit="return confirm('Delete this coupon?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t">
            {{ $coupons->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-20 h-20 mx-auto rounded-full bg-purple-100 flex items-center justify-center mb-4">
                <i class="fas fa-ticket-alt text-4xl text-purple-500"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No coupons yet</h3>
            <p class="text-gray-500 mb-4">Create coupon codes to offer discounts</p>
            <a href="{{ route('user.shop.coupons.create') }}" class="inline-flex items-center px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                <i class="fas fa-plus mr-2"></i> Create Your First Coupon
            </a>
        </div>
    @endif
</div>

<!-- Plan Limit Info -->
@if($shop->activeSubscription?->plan)
    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                <span class="text-sm text-blue-700">
                    Coupons: {{ $coupons->total() }} / {{ $shop->activeSubscription->plan->max_coupons == -1 ? 'Unlimited' : $shop->activeSubscription->plan->max_coupons }}
                </span>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
    function copyCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            const alert = document.createElement('div');
            alert.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
            alert.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Coupon code copied!';
            document.body.appendChild(alert);
            setTimeout(() => alert.remove(), 2000);
        });
    }
</script>
@endpush
@endsection
