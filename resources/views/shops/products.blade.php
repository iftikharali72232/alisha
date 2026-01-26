@extends('shops.layout')

@section('title', 'Products - ' . $shop->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumbs -->
    <nav class="flex mb-6 text-sm">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li><a href="{{ route('shop.show', $shop->slug) }}" class="text-gray-500 hover:text-pink-600">Home</a></li>
            <li><span class="mx-2 text-gray-400">/</span></li>
            <li class="text-gray-800">All Products</li>
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <aside class="lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Filters</h3>
                
                <form method="GET" action="{{ route('shop.products', $shop->slug) }}">
                    <!-- Search -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-pink-500 focus:border-pink-500"
                            placeholder="Product name...">
                    </div>

                    <!-- Categories -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($categories as $category)
                                <label class="flex items-center">
                                    <input type="radio" name="category" value="{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'checked' : '' }}
                                        class="rounded text-pink-500 focus:ring-pink-500">
                                    <span class="ml-2 text-sm text-gray-600">{{ $category->name }}</span>
                                    <span class="ml-auto text-xs text-gray-400">({{ $category->products_count ?? 0 }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-pink-500 focus:border-pink-500"
                                placeholder="Min">
                            <input type="number" name="max_price" value="{{ request('max_price') }}"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-pink-500 focus:border-pink-500"
                                placeholder="Max">
                        </div>
                    </div>

                    <!-- Sort By -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select name="sort"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                            <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name A-Z</option>
                        </select>
                    </div>

                    <!-- On Sale Only -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="on_sale" value="1" {{ request('on_sale') ? 'checked' : '' }}
                                class="rounded text-pink-500 focus:ring-pink-500">
                            <span class="ml-2 text-sm text-gray-600">On Sale Only</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition">
                        Apply Filters
                    </button>
                    
                    @if(request()->hasAny(['search', 'category', 'min_price', 'max_price', 'sort', 'on_sale']))
                        <a href="{{ route('shop.products', $shop->slug) }}"
                            class="block text-center mt-2 text-gray-600 hover:text-pink-600 text-sm">
                            Clear Filters
                        </a>
                    @endif
                </form>
            </div>
        </aside>

        <!-- Products Grid -->
        <main class="flex-1">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">All Products</h1>
                    <p class="text-gray-600">{{ $products->total() }} products found</p>
                </div>
                
                <!-- Mobile Filter Toggle -->
                <button onclick="toggleMobileFilters()" class="lg:hidden px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-filter mr-2"></i>Filters
                </button>
            </div>

            @if($products->count() > 0)
                <!-- Products -->
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                    @foreach($products as $product)
                        @include('shops.partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->withQueryString()->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">No products found</h3>
                    <p class="text-gray-500 mb-4">Try adjusting your filters or search criteria</p>
                    <a href="{{ route('shop.products', $shop->slug) }}"
                        class="inline-block px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                        View All Products
                    </a>
                </div>
            @endif
        </main>
    </div>
</div>

<!-- Mobile Filters Modal -->
<div id="mobileFilters" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="absolute right-0 top-0 h-full w-80 bg-white overflow-y-auto">
        <div class="p-4 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Filters</h3>
            <button onclick="toggleMobileFilters()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-4">
            <!-- Same filter form here for mobile -->
        </div>
    </div>
</div>

<script>
function toggleMobileFilters() {
    const modal = document.getElementById('mobileFilters');
    modal.classList.toggle('hidden');
}
</script>
@endsection
