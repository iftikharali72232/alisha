@extends('shops.layout')

@section('title', $offer->name . ' - ' . $shop->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Offer Header -->
    <div class="bg-gradient-to-r from-orange-400 to-red-500 rounded-xl p-6 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $offer->name }}</h1>
                <p class="text-orange-100 mb-4">{{ $offer->description }}</p>
                <div class="flex items-center space-x-4">
                    <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-medium">
                        @if($offer->discount_type === 'percentage')
                            {{ $offer->discount_value }}% OFF
                        @else
                            Rs. {{ number_format($offer->discount_value) }} OFF
                        @endif
                    </span>
                    <span class="text-sm">
                        @if($offer->ends_at->isToday())
                            <i class="fas fa-clock mr-1"></i> Ends Today!
                        @else
                            <i class="fas fa-clock mr-1"></i> Ends {{ $offer->ends_at->diffForHumans() }}
                        @endif
                    </span>
                </div>
            </div>
            @if($offer->product)
                <div class="hidden md:block">
                    <img src="{{ Storage::url($offer->product->featured_image) }}" alt="{{ $offer->product->name }}"
                         class="w-32 h-32 rounded-lg object-cover shadow-lg">
                </div>
            @endif
        </div>
    </div>

    @if($offer->product)
        <!-- Single Product Offer -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="md:flex">
                    @if($offer->product->featured_image)
                        <div class="md:w-1/2">
                            <img src="{{ Storage::url($offer->product->featured_image) }}" alt="{{ $offer->product->name }}"
                                 class="w-full h-64 md:h-full object-cover">
                        </div>
                    @endif
                    <div class="md:w-1/2 p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $offer->product->name }}</h2>

                        @if($offer->product->short_description)
                            <p class="text-gray-600 mb-6">{{ $offer->product->short_description }}</p>
                        @endif

                        <div class="mb-6">
                            @php
                                $originalPrice = $offer->product->getFinalPrice();
                                $discountedPrice = $offer->calculateDiscountedPrice($originalPrice);
                                $savings = $originalPrice - $discountedPrice;
                            @endphp

                            <div class="flex items-center space-x-3 mb-2">
                                <span class="text-3xl font-bold text-pink-600">Rs. {{ number_format($discountedPrice) }}</span>
                                <span class="text-xl text-gray-400 line-through">Rs. {{ number_format($originalPrice) }}</span>
                            </div>
                            <p class="text-green-600 font-medium">You save Rs. {{ number_format($savings) }}!</p>
                        </div>

                        <div class="flex space-x-4">
                            <a href="{{ route('shop.product', [$shop->slug, $offer->product->slug]) }}"
                               class="flex-1 bg-pink-600 text-white px-6 py-3 rounded-lg hover:bg-pink-700 font-medium text-center">
                                <i class="fas fa-eye mr-2"></i> View Product
                            </a>
                            @if($shop->whatsapp_number)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $shop->whatsapp_number) }}?text={{ urlencode('Hi! I\'m interested in the offer: ' . $offer->name) }}"
                                   target="_blank"
                                   class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 font-medium">
                                    <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- General Offer - Show Related Products -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Featured Products</h2>

            @if($products->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                    @foreach($products as $product)
                        @include('shops.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No products available</h3>
                    <p class="text-gray-500">Check back later for products under this offer.</p>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection