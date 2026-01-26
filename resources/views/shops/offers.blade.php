@extends('shops.layout')

@section('title', 'Special Offers - ' . $shop->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Special Offers</h1>
        <p class="text-gray-600">Don't miss out on our limited-time deals</p>
    </div>

    @if($offers->count() > 0)
        <!-- Offers Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($offers as $offer)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <!-- Offer Badge -->
                    <div class="bg-gradient-to-r from-orange-400 to-red-500 px-4 py-2">
                        <div class="flex items-center justify-between text-white">
                            <span class="font-semibold">
                                @if($offer->discount_type === 'percentage')
                                    {{ $offer->discount_value }}% OFF
                                @else
                                    Rs. {{ number_format($offer->discount_value) }} OFF
                                @endif
                            </span>
                            <span class="text-sm">
                                @if($offer->ends_at->isToday())
                                    Ends Today!
                                @else
                                    {{ $offer->ends_at->diffForHumans() }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="p-6">
                        @if($offer->product)
                            <div class="flex items-start space-x-4">
                                @if($offer->product->featured_image)
                                    <img src="{{ Storage::url($offer->product->featured_image) }}" alt="{{ $offer->product->name }}"
                                         class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800 mb-2">{{ $offer->product->name }}</h3>
                                    <div class="mb-3">
                                        @php
                                            $originalPrice = $offer->product->getFinalPrice();
                                            $discountedPrice = $offer->calculateDiscountedPrice($originalPrice);
                                        @endphp
                                        <span class="text-lg font-bold text-pink-600">Rs. {{ number_format($discountedPrice) }}</span>
                                        <span class="text-sm text-gray-400 line-through ml-2">Rs. {{ number_format($originalPrice) }}</span>
                                    </div>
                                    <a href="{{ route('shop.product', [$shop->slug, $offer->product->slug]) }}"
                                       class="inline-flex items-center px-4 py-2 bg-pink-600 text-white text-sm rounded-lg hover:bg-pink-700 transition-colors">
                                        <i class="fas fa-eye mr-2"></i> View Product
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-gift text-4xl text-gray-300 mb-4"></i>
                                <h3 class="font-semibold text-gray-800 mb-2">{{ $offer->name }}</h3>
                                <p class="text-gray-600 text-sm">{{ $offer->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- No Offers -->
        <div class="text-center py-12">
            <div class="mb-4">
                <i class="fas fa-percent text-6xl text-gray-300"></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-700 mb-2">No active offers</h2>
            <p class="text-gray-500 mb-6">Check back later for special deals and discounts.</p>
            <a href="{{ route('shop.products', $shop->slug) }}" class="inline-flex items-center px-6 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                <i class="fas fa-th mr-2"></i> Browse Products
            </a>
        </div>
    @endif
</div>
@endsection