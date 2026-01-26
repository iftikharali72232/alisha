@extends('shops.layout')

@section('title', 'Search Results - ' . $shop->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Search Results</h1>
        <p class="text-gray-600">Showing results for "{{ $query }}"</p>
    </div>

    @if($products->count() > 0)
        <!-- Results Count -->
        <div class="mb-6">
            <p class="text-gray-600">{{ $products->total() }} product{{ $products->total() !== 1 ? 's' : '' }} found</p>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
            @foreach($products as $product)
                @include('shops.partials.product-card', ['product' => $product])
            @endforeach
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @endif
    @else
        <!-- No Results -->
        <div class="text-center py-12">
            <div class="mb-4">
                <i class="fas fa-search text-6xl text-gray-300"></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-700 mb-2">No products found</h2>
            <p class="text-gray-500 mb-6">We couldn't find any products matching "{{ $query }}"</p>
            <div class="space-x-4">
                <a href="{{ route('shop.products', $shop->slug) }}" class="inline-flex items-center px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                    <i class="fas fa-th mr-2"></i> Browse All Products
                </a>
                <a href="{{ route('shop.show', $shop->slug) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    <i class="fas fa-home mr-2"></i> Back to Home
                </a>
            </div>
        </div>
    @endif
</div>
@endsection