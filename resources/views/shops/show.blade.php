@extends('shops.layout')

@section('title', $shop->name)

@section('content')
<!-- Slider -->
@if($shop->sliders->count() > 0)
    <div class="relative" x-data="{ currentSlide: 0 }" x-init="setInterval(() => currentSlide = (currentSlide + 1) % {{ $shop->sliders->count() }}, 5000)">
        <div class="overflow-hidden">
            @foreach($shop->sliders as $index => $slider)
                <div x-show="currentSlide === {{ $index }}" 
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="relative h-64 md:h-96">
                    <img src="{{ Storage::url($slider->image) }}" alt="{{ $slider->title }}" class="w-full h-full object-cover">
                    @if($slider->title || $slider->subtitle)
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                            <div class="text-center text-white px-4">
                                @if($slider->title)
                                    <h2 class="text-3xl md:text-5xl font-bold mb-2">{{ $slider->title }}</h2>
                                @endif
                                @if($slider->subtitle)
                                    <p class="text-lg md:text-xl mb-4">{{ $slider->subtitle }}</p>
                                @endif
                                @if($slider->button_text && $slider->button_link)
                                    <a href="{{ $slider->button_link }}" class="inline-block px-6 py-3 bg-pink-500 text-white rounded-lg hover:bg-pink-600">
                                        {{ $slider->button_text }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <!-- Slider Dots -->
        @if($shop->sliders->count() > 1)
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                @foreach($shop->sliders as $index => $slider)
                    <button @click="currentSlide = {{ $index }}" 
                            :class="currentSlide === {{ $index }} ? 'bg-white' : 'bg-white/50'"
                            class="w-3 h-3 rounded-full transition-colors"></button>
                @endforeach
            </div>
        @endif
    </div>
@endif

<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Active Offers Banner -->
    @if($activeOffers->count() > 0)
        <div class="mb-8">
            <div class="bg-gradient-to-r from-orange-500 to-pink-500 rounded-xl p-6 text-white">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <span class="offer-badge inline-block px-3 py-1 bg-white/20 rounded-full text-sm mb-2">
                            <i class="fas fa-fire mr-1"></i> Limited Time Offer
                        </span>
                        <h2 class="text-2xl md:text-3xl font-bold">{{ $activeOffers->first()->name }}</h2>
                        <p class="text-white/80">{{ $activeOffers->first()->description }}</p>
                    </div>
                    <div class="mt-4 md:mt-0 text-center">
                        <div class="text-4xl font-bold">
                            @if($activeOffers->first()->type === 'percentage')
                                {{ $activeOffers->first()->value }}% OFF
                            @else
                                Rs. {{ number_format($activeOffers->first()->value) }} OFF
                            @endif
                        </div>
                        <p class="text-sm text-white/80">Ends {{ $activeOffers->first()->ends_at->diffForHumans() }}</p>
                        <a href="{{ route('shop.offer', [$shop->slug, $activeOffers->first()->id]) }}" 
                           class="inline-block mt-2 px-6 py-2 bg-white text-pink-600 rounded-lg font-medium hover:bg-gray-100">
                            Shop Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Featured Products -->
    @if($featuredProducts->count() > 0)
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Featured Products</h2>
                <a href="{{ route('shop.products', $shop->slug) }}" class="text-pink-600 hover:underline">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                @foreach($featuredProducts as $product)
                    @include('shops.partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif

    <!-- Categories -->
    @if($shop->categories->count() > 0)
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Shop by Category</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($shop->categories->where('parent_id', null)->take(6) as $category)
                    <a href="{{ route('shop.category', [$shop->slug, $category->slug]) }}" 
                       class="group text-center p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition">
                        @if($category->image)
                            <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" 
                                 class="w-20 h-20 mx-auto rounded-full object-cover mb-3 group-hover:scale-105 transition">
                        @else
                            <div class="w-20 h-20 mx-auto rounded-full bg-pink-100 flex items-center justify-center mb-3 group-hover:scale-105 transition">
                                <i class="fas fa-folder text-pink-500 text-2xl"></i>
                            </div>
                        @endif
                        <h3 class="font-medium text-gray-800 group-hover:text-pink-600">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $category->products_count ?? 0 }} Products</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- New Arrivals -->
    @if($newArrivals->count() > 0)
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">New Arrivals</h2>
                <a href="{{ route('shop.products', [$shop->slug, 'sort' => 'newest']) }}" class="text-pink-600 hover:underline">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                @foreach($newArrivals as $product)
                    @include('shops.partials.product-card', ['product' => $product, 'badge' => 'New'])
                @endforeach
            </div>
        </section>
    @endif

    <!-- Best Sellers -->
    @if($bestSellers->count() > 0)
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Best Sellers</h2>
                <a href="{{ route('shop.products', [$shop->slug, 'sort' => 'popular']) }}" class="text-pink-600 hover:underline">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                @foreach($bestSellers as $product)
                    @include('shops.partials.product-card', ['product' => $product, 'badge' => 'Best Seller'])
                @endforeach
            </div>
        </section>
    @endif

    <!-- Gallery -->
    @if($shop->galleries->count() > 0)
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Gallery</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($shop->galleries->take(8) as $gallery)
                    <div class="aspect-square rounded-lg overflow-hidden">
                        <img src="{{ Storage::url($gallery->image) }}" alt="{{ $gallery->title }}" 
                             class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Reviews -->
    @if($reviews->count() > 0)
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Customer Reviews</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($reviews as $review)
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center mr-3">
                                <span class="text-pink-600 font-medium">{{ substr($review->customer->name ?? 'A', 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $review->customer->name ?? 'Anonymous' }}</p>
                                <div class="flex text-yellow-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
                        <p class="text-gray-400 text-xs mt-2">{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
