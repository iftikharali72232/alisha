@extends('user.shop.layout')

@section('title', 'Edit Product - ' . $product->name)

@section('shop-content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Product</h1>
            <p class="text-gray-600">Update product details for {{ $product->name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('user.shop.products.show', $product) }}" 
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-eye mr-2"></i>View
            </a>
            <a href="{{ route('user.shop.products.index') }}" 
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to Products
            </a>
        </div>
    </div>
</div>

<form action="{{ route('user.shop.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Product Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                            value="{{ old('name', $product->name) }}"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('name') border-red-500 @enderror"
                            placeholder="Enter product name">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                        <input type="text" name="sku" id="sku"
                            value="{{ old('sku', $product->sku) }}"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="Stock keeping unit">
                    </div>

                    <div>
                        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                        <textarea name="short_description" id="short_description" rows="2"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="Brief product description (max 500 characters)">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Full Description</label>
                        <textarea name="description" id="description" rows="6"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="Detailed product description">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pricing</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                            Price <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">{{ $shop->currency }}</span>
                            <input type="number" name="price" id="price" required step="0.01" min="0"
                                value="{{ old('price', $product->price) }}"
                                class="w-full border rounded-lg pl-14 pr-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('price') border-red-500 @enderror">
                        </div>
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="compare_price" class="block text-sm font-medium text-gray-700 mb-1">Compare at Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">{{ $shop->currency }}</span>
                            <input type="number" name="compare_price" id="compare_price" step="0.01" min="0"
                                value="{{ old('compare_price', $product->compare_price) }}"
                                class="w-full border rounded-lg pl-14 pr-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Original price to show discount</p>
                    </div>

                    <div>
                        <label for="cost_price" class="block text-sm font-medium text-gray-700 mb-1">Cost Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">{{ $shop->currency }}</span>
                            <input type="number" name="cost_price" id="cost_price" step="0.01" min="0"
                                value="{{ old('cost_price', $product->cost_price) }}"
                                class="w-full border rounded-lg pl-14 pr-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">For profit calculation</p>
                    </div>
                </div>

                <!-- Tax -->
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_taxable" id="is_taxable" value="1" 
                            {{ old('is_taxable', $product->is_taxable) ? 'checked' : '' }}
                            class="rounded text-pink-500 focus:ring-pink-500">
                        <label for="is_taxable" class="text-sm text-gray-700">Charge tax on this product</label>
                    </div>
                    <div>
                        <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-1">Tax Rate (%)</label>
                        <input type="number" name="tax_rate" id="tax_rate" step="0.01" min="0" max="100"
                            value="{{ old('tax_rate', $product->tax_rate) }}"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="Leave empty to use shop default">
                    </div>
                </div>
            </div>

            <!-- Inventory -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Inventory</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="track_inventory" id="track_inventory" value="1"
                            {{ old('track_inventory', $product->track_inventory) ? 'checked' : '' }}
                            class="rounded text-pink-500 focus:ring-pink-500">
                        <label for="track_inventory" class="text-sm text-gray-700">Track inventory for this product</label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                                Quantity <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="quantity" id="quantity" required min="0"
                                value="{{ old('quantity', $product->quantity) }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                        </div>

                        <div>
                            <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700 mb-1">Low Stock Alert</label>
                            <input type="number" name="low_stock_threshold" id="low_stock_threshold" min="0"
                                value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                                placeholder="Alert when stock falls below">
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="allow_backorder" id="allow_backorder" value="1"
                            {{ old('allow_backorder', $product->allow_backorder) ? 'checked' : '' }}
                            class="rounded text-pink-500 focus:ring-pink-500">
                        <label for="allow_backorder" class="text-sm text-gray-700">Allow customers to purchase when out of stock</label>
                    </div>
                </div>
            </div>

            <!-- Shipping -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Shipping</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Weight</label>
                        <div class="flex gap-2">
                            <input type="number" name="weight" id="weight" step="0.01" min="0"
                                value="{{ old('weight', $product->weight) }}"
                                class="flex-1 border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                                placeholder="0.00">
                            <select name="weight_unit" class="border rounded-lg px-3 py-2 focus:ring-pink-500 focus:border-pink-500">
                                <option value="kg" {{ old('weight_unit', $product->weight_unit) === 'kg' ? 'selected' : '' }}>kg</option>
                                <option value="g" {{ old('weight_unit', $product->weight_unit) === 'g' ? 'selected' : '' }}>g</option>
                                <option value="lb" {{ old('weight_unit', $product->weight_unit) === 'lb' ? 'selected' : '' }}>lb</option>
                                <option value="oz" {{ old('weight_unit', $product->weight_unit) === 'oz' ? 'selected' : '' }}>oz</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dimensions (cm)</label>
                        <div class="grid grid-cols-3 gap-2">
                            <input type="number" name="dimensions[length]" step="0.01" min="0"
                                value="{{ old('dimensions.length', $product->dimensions['length'] ?? '') }}"
                                class="border rounded-lg px-3 py-2 focus:ring-pink-500 focus:border-pink-500"
                                placeholder="Length">
                            <input type="number" name="dimensions[width]" step="0.01" min="0"
                                value="{{ old('dimensions.width', $product->dimensions['width'] ?? '') }}"
                                class="border rounded-lg px-3 py-2 focus:ring-pink-500 focus:border-pink-500"
                                placeholder="Width">
                            <input type="number" name="dimensions[height]" step="0.01" min="0"
                                value="{{ old('dimensions.height', $product->dimensions['height'] ?? '') }}"
                                class="border rounded-lg px-3 py-2 focus:ring-pink-500 focus:border-pink-500"
                                placeholder="Height">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Product Images</h3>
                
                <!-- Featured Image -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                    <div class="flex items-start gap-4">
                        <div id="featured-preview" class="w-32 h-32 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                            @if($product->featured_image)
                                <img src="{{ Storage::url($product->featured_image) }}" alt="Featured" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-image text-3xl text-gray-400"></i>
                            @endif
                        </div>
                        <div>
                            <label class="cursor-pointer bg-white px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition inline-block">
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-upload mr-2"></i>{{ $product->featured_image ? 'Change Image' : 'Upload Image' }}
                                </span>
                                <input type="file" name="featured_image" accept="image/*" class="hidden"
                                    onchange="previewImage(this, 'featured-preview')">
                            </label>
                            <p class="text-xs text-gray-500 mt-2">Recommended: 800x800px, max 2MB</p>
                        </div>
                    </div>
                </div>

                <!-- Gallery Images -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                    
                    @if($product->gallery_images && count($product->gallery_images) > 0)
                        <div class="grid grid-cols-4 gap-3 mb-4">
                            @foreach($product->gallery_images as $index => $image)
                                <div class="relative group">
                                    <img src="{{ Storage::url($image) }}" alt="Gallery" class="w-full h-24 object-cover rounded-lg">
                                    <form action="{{ route('user.shop.products.deleteImage', $product) }}" method="POST" class="absolute top-1 right-1">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="index" value="{{ $index }}">
                                        <button type="submit" onclick="return confirm('Delete this image?')"
                                            class="w-6 h-6 bg-red-500 text-white rounded-full text-xs opacity-0 group-hover:opacity-100 transition">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <div id="gallery-preview" class="grid grid-cols-4 gap-3 mb-4"></div>
                    <label class="cursor-pointer bg-white px-4 py-2 border border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition inline-flex items-center">
                        <i class="fas fa-plus mr-2 text-gray-400"></i>
                        <span class="text-sm text-gray-600">Add Gallery Images</span>
                        <input type="file" name="images[]" accept="image/*" multiple class="hidden"
                            onchange="previewGallery(this)">
                    </label>
                </div>
            </div>

            <!-- SEO -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-search text-gray-400 mr-2"></i>SEO Settings
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title"
                            value="{{ old('meta_title', $product->meta_title) }}"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="SEO title (leave empty to use product name)">
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="3"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="SEO description for search engines">{{ old('meta_description', $product->meta_description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Status</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                            {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                            class="rounded text-pink-500 focus:ring-pink-500 mr-2">
                        <label for="is_active" class="text-sm text-gray-700">Active</label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" 
                            {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                            class="rounded text-pink-500 focus:ring-pink-500 mr-2">
                        <label for="is_featured" class="text-sm text-gray-700">Featured Product</label>
                    </div>
                </div>

                <!-- Stats -->
                <div class="mt-4 pt-4 border-t space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Views:</span>
                        <span class="font-medium">{{ number_format($product->view_count) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Orders:</span>
                        <span class="font-medium">{{ number_format($product->order_count) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Created:</span>
                        <span class="font-medium">{{ $product->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Organization -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Organization</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" id="category_id"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                        <select name="brand_id" id="brand_id"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Active Offer -->
            @if($product->activeOffer)
            <div class="bg-gradient-to-br from-pink-50 to-purple-50 rounded-lg shadow p-6 border border-pink-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                    <i class="fas fa-tag text-pink-500 mr-2"></i>Active Offer
                </h3>
                <p class="text-sm text-gray-600 mb-2">{{ $product->activeOffer->name }}</p>
                <p class="text-lg font-bold text-pink-600">
                    @if($product->activeOffer->type === 'percentage')
                        {{ $product->activeOffer->value }}% OFF
                    @else
                        {{ $shop->currency }} {{ number_format($product->activeOffer->value) }} OFF
                    @endif
                </p>
                <p class="text-xs text-gray-500 mt-2">
                    Ends: {{ $product->activeOffer->ends_at->format('M d, Y') }}
                </p>
            </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <button type="submit"
                    class="w-full bg-pink-500 text-white px-4 py-3 rounded-lg hover:bg-pink-600 transition font-semibold">
                    <i class="fas fa-save mr-2"></i>Update Product
                </button>
                
                <div class="mt-4 pt-4 border-t">
                    <form action="{{ route('user.shop.products.destroy', $product) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this product?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-50 text-red-600 px-4 py-2 rounded-lg hover:bg-red-100 transition">
                            <i class="fas fa-trash mr-2"></i>Delete Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function previewGallery(input) {
    const preview = document.getElementById('gallery-preview');
    preview.innerHTML = '';
    
    if (input.files) {
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'w-full h-24 bg-gray-100 rounded-lg overflow-hidden';
                div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                preview.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
    }
}
</script>
@endpush
@endsection
