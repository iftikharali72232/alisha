@extends('shops.layout')

@section('title', $category->name . ' - ' . $shop->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumbs -->
    <nav class="flex mb-6 text-sm">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li><a href="{{ route('shop.show', $shop->slug) }}" class="text-gray-500 hover:text-pink-600">Home</a></li>
            <li><span class="mx-2 text-gray-400">/</span></li>
            <li><a href="{{ route('shop.products', $shop->slug) }}" class="text-gray-500 hover:text-pink-600">Products</a></li>
            <li><span class="mx-2 text-gray-400">/</span></li>
            <li class="text-gray-800">{{ $category->name }}</li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $category->name }}</h1>
        @if($category->description)
            <p class="text-gray-600 text-lg">{{ $category->description }}</p>
        @endif
        <p class="text-gray-500 mt-2">{{ $products->total() }} products</p>
    </div>

    @if($products->count() > 0)
        <!-- Products Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
            @foreach($products as $product)
                @include('shops.partials.product-card', ['product' => $product])
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-700 mb-2">No products found</h3>
            <p class="text-gray-500 mb-4">This category doesn't have any products yet.</p>
            <a href="{{ route('shop.products', $shop->slug) }}"
                class="inline-block px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                View All Products
            </a>
        </div>
    @endif
</div>
@endsection