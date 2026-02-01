@if(!isset($shop) || $product->shop_id == $shop->id)
    <div class="product-card bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-lg block">
    <!-- Image -->
    <div class="relative aspect-square overflow-hidden">
        @if($product->featured_image)
            <a href="{{ route('shop.product', [$shop->slug, $product->slug]) }}">
                <img src="{{ Storage::url($product->featured_image) }}" alt="{{ $product->name }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </a>
        @else
            <a href="{{ route('shop.product', [$shop->slug, $product->slug]) }}">
                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-image text-4xl text-gray-300"></i>
                </div>
            </a>
        @endif

        <!-- Badges -->
        <div class="absolute top-2 left-2 flex flex-col gap-1">
            @if(isset($badge))
                <span class="px-2 py-1 bg-pink-500 text-white text-xs rounded-full">{{ $badge }}</span>
            @endif
            
            @if($product->hasActiveOffer())
                <span class="offer-badge px-2 py-1 bg-orange-500 text-white text-xs rounded-full">
                    @if($product->activeOffer->type === 'percentage')
                        {{ $product->activeOffer->value }}% OFF
                    @else
                        Rs. {{ number_format($product->activeOffer->value) }} OFF
                    @endif
                </span>
            @elseif($product->compare_price && $product->compare_price > $product->price)
                @php
                    $discountPercent = round((($product->compare_price - $product->price) / $product->compare_price) * 100);
                @endphp
                <span class="px-2 py-1 bg-green-500 text-white text-xs rounded-full">{{ $discountPercent }}% OFF</span>
            @endif
            
            @if($product->track_quantity && $product->quantity <= 0)
                <span class="px-2 py-1 bg-red-500 text-white text-xs rounded-full">Out of Stock</span>
            @elseif($product->track_quantity && $product->quantity <= $product->low_stock_threshold)
                <span class="px-2 py-1 bg-yellow-500 text-white text-xs rounded-full">Low Stock</span>
            @endif
        </div>

        <!-- Quick Add Button -->
        @if(!$product->track_quantity || $product->quantity > 0)
            <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <form action="{{ route('shop.cart.add', [$shop->slug, $product->slug]) }}" method="POST" class="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-10 h-10 bg-pink-500 text-white rounded-full flex items-center justify-center hover:bg-pink-600 shadow-lg">
                        <i class="fas fa-plus"></i>
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Content -->
    <div class="p-4">
        <a href="{{ route('shop.product', [$shop->slug, $product->slug]) }}">
            <h3 class="font-medium text-gray-800 mb-1 line-clamp-2 hover:text-pink-600">{{ $product->name }}</h3>
        </a>
        
        @if($product->category)
            <p class="text-xs text-gray-500 mb-2">{{ $product->category->name }}</p>
        @endif

        <div class="flex items-center justify-between">
            <div>
                @php
                    $finalPrice = $product->getFinalPrice();
                    $showCompare = $product->compare_price && $product->compare_price > $finalPrice;
                @endphp
                
                <span class="text-lg font-bold text-pink-600">Rs. {{ number_format($finalPrice) }}</span>
                @if($showCompare)
                    <span class="text-sm text-gray-400 line-through ml-1">Rs. {{ number_format($product->compare_price) }}</span>
                @endif
            </div>
            
            @if($product->reviews_avg_rating)
                <div class="flex items-center text-sm">
                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                    <span class="text-gray-600">{{ number_format($product->reviews_avg_rating, 1) }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
@endif
