@extends('shops.layout')

@section('title', 'Categories - ' . $shop->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Shop by Category</h1>
        <p class="text-gray-600">Browse our product categories</p>
    </div>

    @if($categories->count() > 0)
        <!-- Categories Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
            @foreach($categories as $category)
                <a href="{{ route('shop.category', [$shop->slug, $category->slug]) }}"
                   class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100 hover:border-pink-200">
                    <!-- Category Image/Icon -->
                    <div class="aspect-square bg-gradient-to-br from-pink-50 to-purple-50 flex items-center justify-center p-6 group-hover:from-pink-100 group-hover:to-purple-100 transition-colors">
                        @if($category->image)
                            <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover rounded-lg">
                        @else
                            <div class="text-center">
                                <i class="fas fa-tag text-4xl text-pink-400 mb-2"></i>
                                <div class="w-16 h-1 bg-pink-300 rounded-full mx-auto"></div>
                            </div>
                        @endif
                    </div>

                    <!-- Category Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 group-hover:text-pink-600 transition-colors mb-1">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $category->products_count }} product{{ $category->products_count !== 1 ? 's' : '' }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <!-- No Categories -->
        <div class="text-center py-12">
            <div class="mb-4">
                <i class="fas fa-tags text-6xl text-gray-300"></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-700 mb-2">No categories found</h2>
            <p class="text-gray-500 mb-6">We're working on adding categories to our store.</p>
            <a href="{{ route('shop.products', $shop->slug) }}" class="inline-flex items-center px-6 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                <i class="fas fa-th mr-2"></i> Browse All Products
            </a>
        </div>
    @endif
</div>
@endsection