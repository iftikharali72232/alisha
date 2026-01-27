@extends('user.shop.layout')

@section('title', 'Products')
@section('page-title', 'Products')

@section('shop-content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Products</h1>
            <p class="text-gray-600">Manage your product catalog</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('user.shop.products.create') }}" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700">
                <i class="fas fa-plus mr-2"></i> Add Product
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form action="{{ route('user.shop.products.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." 
                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
        </div>
        <div>
            <select name="category" class="border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="status" class="border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                <option value="">All Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-search mr-2"></i> Filter
        </button>
    </form>
</div>

<!-- Products Grid/List -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    @if($products->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($product->featured_image)
                                        <img src="{{ Storage::url($product->featured_image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded object-cover">
                                    @else
                                        <div class="w-12 h-12 rounded bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500">SKU: {{ $product->sku ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">{{ $product->category?->name ?? 'Uncategorized' }}</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Rs. {{ number_format($product->price) }}</div>
                                @if($product->compare_price)
                                    <div class="text-xs text-gray-500 line-through">Rs. {{ number_format($product->compare_price) }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">{{ $product->cost_price ? 'Rs. ' . number_format($product->cost_price) : '-' }}</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($product->track_quantity)
                                    <span class="text-sm {{ $product->quantity <= $product->low_stock_threshold ? 'text-red-600 font-medium' : 'text-gray-600' }}">
                                        {{ $product->quantity }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">Not tracked</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">{{ $product->weight ? $product->weight . ' ' . ($product->weight_unit ?? 'kg') : '-' }}</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <form action="{{ route('user.shop.products.toggle-status', $product) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($product->is_featured)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">
                                        <i class="fas fa-star mr-1"></i>Featured
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">{{ number_format($product->view_count ?? 0) }}</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">{{ $product->orderItems->count() }}</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">{{ $product->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="{{ route('user.shop.products.show', $product) }}" class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                                <a href="{{ route('user.shop.products.edit', $product) }}" class="inline-flex items-center px-3 py-1 text-xs font-medium text-rose-700 bg-rose-100 rounded-lg hover:bg-rose-200 transition-colors duration-200">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <form action="{{ route('user.shop.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition-colors duration-200">
                                        <i class="fas fa-trash mr-1"></i>Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <i class="fas fa-box text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No products yet</h3>
            <p class="text-gray-500 mb-4">Start adding products to your catalog</p>
            <a href="{{ route('user.shop.products.create') }}" class="inline-flex items-center px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                <i class="fas fa-plus mr-2"></i> Add Your First Product
            </a>
        </div>
    @endif
</div>

<!-- Plan Limit Info -->
@if($shop->activeSubscription?->plan)
    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                <span class="text-sm text-blue-700">
                    Products: {{ $products->total() }} / {{ $shop->activeSubscription->plan->max_products == -1 ? 'Unlimited' : $shop->activeSubscription->plan->max_products }}
                </span>
            </div>
            @if($shop->activeSubscription->plan->max_products != -1 && $products->total() >= $shop->activeSubscription->plan->max_products)
                <a href="{{ route('user.shop.subscription') }}" class="text-sm text-blue-600 hover:underline">
                    Upgrade for more products
                </a>
            @endif
        </div>
    </div>
@endif
@endsection
