@extends('shops.layout')

@section('title', $product->name . ' - ' . $shop->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('shop.show', $shop->slug) }}" class="text-gray-500 hover:text-pink-600">Home</a></li>
            <li><i class="fas fa-chevron-right text-gray-300 text-xs"></i></li>
            @if($product->category)
                <li><a href="{{ route('shop.category', [$shop->slug, $product->category->slug]) }}" class="text-gray-500 hover:text-pink-600">{{ $product->category->name }}</a></li>
                <li><i class="fas fa-chevron-right text-gray-300 text-xs"></i></li>
            @endif
            <li class="text-gray-800">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Images -->
        <div x-data="{ mainImage: '{{ Storage::url($product->featured_image) }}' }">
            <div class="aspect-square bg-gray-100 rounded-xl overflow-hidden mb-4">
                <img :src="mainImage" alt="{{ $product->name }}" class="w-full h-full object-contain">
            </div>
            
            @if($product->galleries && $product->galleries->count() > 0)
                <div class="grid grid-cols-5 gap-2">
                    <button @click="mainImage = '{{ Storage::url($product->featured_image) }}'"
                            class="aspect-square rounded-lg overflow-hidden border-2 border-transparent hover:border-pink-500 focus:border-pink-500">
                        <img src="{{ Storage::url($product->featured_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </button>
                    @foreach($product->galleries as $gallery)
                        <button @click="mainImage = '{{ Storage::url($gallery->image) }}'"
                                class="aspect-square rounded-lg overflow-hidden border-2 border-transparent hover:border-pink-500 focus:border-pink-500">
                            <img src="{{ Storage::url($gallery->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Details -->
        <div>
            <!-- Badges -->
            <div class="flex flex-wrap gap-2 mb-4">
                @if($product->hasActiveOffer())
                    <span class="offer-badge px-3 py-1 bg-orange-500 text-white text-sm rounded-full">
                        <i class="fas fa-fire mr-1"></i>
                        @if($product->activeOffer->type === 'percentage')
                            {{ $product->activeOffer->value }}% OFF
                        @else
                            Rs. {{ number_format($product->activeOffer->value) }} OFF
                        @endif
                        - Ends {{ $product->activeOffer->ends_at->diffForHumans() }}
                    </span>
                @endif
                
                @if($product->is_featured)
                    <span class="px-3 py-1 bg-pink-500 text-white text-sm rounded-full">Featured</span>
                @endif
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
            
            @if($product->brand)
                <p class="text-gray-500 mb-4">by <span class="font-medium">{{ $product->brand->name }}</span></p>
            @endif

            <!-- Rating -->
            @if($product->reviews->count() > 0)
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $product->reviews->avg('rating') ? '' : 'text-gray-300' }}"></i>
                        @endfor
                    </div>
                    <span class="ml-2 text-gray-600">({{ $product->reviews->count() }} reviews)</span>
                </div>
            @endif

            <!-- Price -->
            <div class="mb-6">
                @php
                    $finalPrice = $product->getFinalPrice();
                    $showCompare = $product->compare_price && $product->compare_price > $finalPrice;
                @endphp
                
                <span class="text-4xl font-bold text-pink-600">Rs. {{ number_format($finalPrice) }}</span>
                @if($showCompare)
                    <span class="text-xl text-gray-400 line-through ml-2">Rs. {{ number_format($product->compare_price) }}</span>
                    @php
                        $savings = $product->compare_price - $finalPrice;
                        $savingsPercent = round(($savings / $product->compare_price) * 100);
                    @endphp
                    <span class="ml-2 text-green-600 font-medium">You save Rs. {{ number_format($savings) }} ({{ $savingsPercent }}%)</span>
                @endif
                
                @if($product->tax_rate > 0)
                    <p class="text-sm text-gray-500 mt-1">+ {{ $product->tax_rate }}% tax</p>
                @endif
            </div>

            <!-- Short Description -->
            @if($product->short_description)
                <p class="text-gray-600 mb-6">{{ $product->short_description }}</p>
            @endif

            <!-- Stock Status -->
            <div class="mb-6">
                @if($product->track_quantity)
                    @if($product->quantity <= 0)
                        <span class="text-red-600"><i class="fas fa-times-circle mr-1"></i> Out of Stock</span>
                    @elseif($product->quantity <= $product->low_stock_threshold)
                        <span class="text-yellow-600"><i class="fas fa-exclamation-circle mr-1"></i> Only {{ $product->quantity }} left in stock</span>
                    @else
                        <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i> In Stock</span>
                    @endif
                @else
                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i> In Stock</span>
                @endif
            </div>

            <!-- Variants (if any) -->
            @if($product->variants && $product->variants->count() > 0)
                <div class="mb-6" x-data="{ selectedVariant: null }">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Option</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->variants as $variant)
                            <button type="button" 
                                    @click="selectedVariant = {{ $variant->id }}"
                                    :class="selectedVariant === {{ $variant->id }} ? 'border-pink-500 bg-pink-50' : 'border-gray-300'"
                                    class="px-4 py-2 border rounded-lg hover:border-pink-500 transition">
                                {{ $variant->name }}
                                @if($variant->price != $product->price)
                                    <span class="text-sm text-gray-500">(Rs. {{ number_format($variant->price) }})</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Add to Cart Form -->
            <form action="{{ route('shop.cart.add', [$shop->slug, $product->slug]) }}" method="POST" class="mb-6">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div class="flex items-center gap-4 mb-4">
                    <label class="text-sm font-medium text-gray-700">Quantity:</label>
                    <div class="flex items-center border rounded-lg">
                        <button type="button" onclick="decrementQty()" class="px-3 py-2 text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" 
                               max="{{ $product->track_quantity ? $product->quantity : 999 }}"
                               class="w-16 text-center border-0 focus:ring-0">
                        <button type="button" onclick="incrementQty()" class="px-3 py-2 text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="flex gap-4">
                    @if(!$product->track_quantity || $product->quantity > 0)
                        <button type="submit" class="flex-1 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700 font-medium shadow-sm border border-pink-600">
                            <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                        </button>
                    @else
                        <button type="button" disabled class="flex-1 py-3 bg-gray-300 text-gray-700 rounded-lg cursor-not-allowed border border-gray-300">
                            <i class="fas fa-times-circle mr-2"></i> Out of Stock
                        </button>
                    @endif
                </div>
            </form>

            <!-- Product Info -->
            <div class="border-t pt-6 space-y-2 text-sm text-gray-600">
                @if($product->sku)
                    <p><strong>SKU:</strong> {{ $product->sku }}</p>
                @endif
                @if($product->category)
                    <p><strong>Category:</strong> <a href="{{ route('shop.category', [$shop->slug, $product->category->slug]) }}" class="text-pink-600 hover:underline">{{ $product->category->name }}</a></p>
                @endif
                @if($product->brand)
                    <p><strong>Brand:</strong> {{ $product->brand->name }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Product Tabs -->
    <div class="mt-12" x-data="{ activeTab: 'description' }">
        <div class="border-b">
            <nav class="flex space-x-8">
                <button @click="activeTab = 'description'" 
                        :class="activeTab === 'description' ? 'border-pink-500 text-pink-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="py-4 px-1 border-b-2 font-medium">
                    Description
                </button>
                <button @click="activeTab = 'reviews'" 
                        :class="activeTab === 'reviews' ? 'border-pink-500 text-pink-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="py-4 px-1 border-b-2 font-medium">
                    Reviews ({{ $product->reviews->count() }})
                </button>
            </nav>
        </div>

        <div class="py-6">
            <!-- Description Tab -->
            <div x-show="activeTab === 'description'">
                <div class="prose max-w-none">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>

            <!-- Reviews Tab -->
            <div x-show="activeTab === 'reviews'">
                @if($product->reviews->count() > 0)
                    <div class="space-y-6">
                        @foreach($product->reviews as $review)
                            <div class="border-b pb-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center">
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
                                    <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="mt-3 text-gray-600">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No reviews yet. Be the first to review this product!</p>
                @endif

                <!-- Add Review Form -->
                @if(session('shop_customer_' . $shop->id))
                    <div class="mt-8 border-t pt-6">
                        <h3 class="font-semibold text-gray-800 mb-4">Write a Review</h3>
                        <form action="{{ route('shop.review.store', [$shop->slug, $product->id]) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                                <div class="flex space-x-1" x-data="{ rating: 5 }">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" @click="rating = {{ $i }}" class="text-2xl focus:outline-none">
                                            <i :class="rating >= {{ $i }} ? 'fas fa-star text-yellow-400' : 'far fa-star text-gray-300'"></i>
                                        </button>
                                    @endfor
                                    <input type="hidden" name="rating" x-model="rating">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Your Review</label>
                                <textarea name="comment" rows="4" required
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"></textarea>
                            </div>
                            <button type="submit" class="px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                                Submit Review
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <section class="mt-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Related Products</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                @foreach($relatedProducts as $related)
                    @include('shops.partials.product-card', ['product' => $related])
                @endforeach
            </div>
        </section>
    @endif
</div>

@push('scripts')
<script>
    function incrementQty() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.max) || 999;
        if (parseInt(input.value) < max) {
            input.value = parseInt(input.value) + 1;
        }
    }
    
    function decrementQty() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
</script>
@endpush
@endsection
