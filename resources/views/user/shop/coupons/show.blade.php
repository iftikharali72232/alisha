@extends('user.shop.layout')

@section('title', 'Coupon Details - ' . $shop->name)

@section('shop-content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Coupon Details</h1>
            <p class="text-gray-600">View coupon information and usage</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('user.shop.coupons.edit', $coupon) }}"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('user.shop.coupons.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to Coupons
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Coupon Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Coupon Information</h3>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    {{ $coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Code</label>
                    <p class="text-lg font-mono font-semibold text-gray-800">{{ $coupon->code }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Name</label>
                    <p class="text-lg font-semibold text-gray-800">{{ $coupon->name }}</p>
                </div>

                @if($coupon->description)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500">Description</label>
                        <p class="text-gray-700">{{ $coupon->description }}</p>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-500">Discount Type</label>
                    <p class="text-gray-800">
                        @if($coupon->type === 'percentage')
                            Percentage ({{ $coupon->value }}%)
                        @elseif($coupon->type === 'fixed')
                            Fixed Amount ({{ $shop->currency }} {{ number_format($coupon->value, 2) }})
                        @else
                            Free Shipping
                        @endif
                    </p>
                </div>

                @if($coupon->min_order_amount)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Minimum Purchase</label>
                        <p class="text-gray-800">{{ $shop->currency }} {{ number_format($coupon->min_order_amount, 2) }}</p>
                    </div>
                @endif

                @if($coupon->max_discount_amount)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Max Discount</label>
                        <p class="text-gray-800">{{ $shop->currency }} {{ number_format($coupon->max_discount_amount, 2) }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Usage Statistics -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Usage Statistics</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $coupon->used_count }}</div>
                    <div class="text-sm text-gray-500">Times Used</div>
                </div>

                @if($coupon->usage_limit)
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600">{{ $coupon->usage_limit - $coupon->used_count }}</div>
                        <div class="text-sm text-gray-500">Uses Remaining</div>
                    </div>
                @endif

                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $coupon->usage_limit_per_customer }}</div>
                    <div class="text-sm text-gray-500">Per Customer</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Validity Period -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Validity Period</h3>

            <div class="space-y-3">
                @if($coupon->starts_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Start Date</label>
                        <p class="text-gray-800">{{ $coupon->starts_at->format('M d, Y H:i') }}</p>
                    </div>
                @else
                    <p class="text-sm text-gray-600">Starts immediately</p>
                @endif

                @if($coupon->ends_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">End Date</label>
                        <p class="text-gray-800">{{ $coupon->ends_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div class="pt-2 border-t">
                        <span class="text-sm
                            {{ $coupon->ends_at->isFuture() ? 'text-green-600' : 'text-red-600' }}">
                            {{ $coupon->ends_at->isFuture() ? 'Active until ' . $coupon->ends_at->diffForHumans() : 'Expired ' . $coupon->ends_at->diffForHumans() }}
                        </span>
                    </div>
                @else
                    <p class="text-sm text-gray-600">No expiration</p>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>

            <div class="space-y-3">
                <form action="{{ route('user.shop.coupons.update', $coupon) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="is_active" value="{{ $coupon->is_active ? '0' : '1' }}">
                    <button type="submit" class="w-full text-left px-4 py-2 rounded-lg border
                        {{ $coupon->is_active ? 'border-red-200 text-red-700 hover:bg-red-50' : 'border-green-200 text-green-700 hover:bg-green-50' }}">
                        <i class="fas {{ $coupon->is_active ? 'fa-ban' : 'fa-check' }} mr-2"></i>
                        {{ $coupon->is_active ? 'Deactivate' : 'Activate' }} Coupon
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection