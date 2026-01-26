@extends('user.shop.layout')

@section('title', 'Add Product')
@section('page-title', 'Add New Product')

@section('shop-content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Add New Product</h1>
            <p class="text-gray-600">Create a new product for your shop</p>
        </div>
        <a href="{{ route('user.shop.products.index') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Products
        </a>
    </div>
</div>

<form action="{{ route('user.shop.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug (URL)</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('slug') border-red-500 @enderror"
                            placeholder="auto-generated-from-name">
                        @error('slug')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                        <textarea name="short_description" id="short_description" rows="2"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">{{ old('short_description') }}</textarea>
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Full Description</label>
                        <textarea name="description" id="description" rows="5"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pricing</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (Rs.) *</label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" required step="0.01" min="0"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('price') border-red-500 @enderror">
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="compare_price" class="block text-sm font-medium text-gray-700 mb-1">Compare at Price (Rs.)</label>
                        <input type="number" name="compare_price" id="compare_price" value="{{ old('compare_price') }}" step="0.01" min="0"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                        <p class="text-xs text-gray-500 mt-1">Original price to show discount</p>
                    </div>
                    
                    <div>
                        <label for="cost_price" class="block text-sm font-medium text-gray-700 mb-1">Cost Price (Rs.)</label>
                        <input type="number" name="cost_price" id="cost_price" value="{{ old('cost_price') }}" step="0.01" min="0"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                        <p class="text-xs text-gray-500 mt-1">For profit calculation (not shown to customers)</p>
                    </div>
                    
                    <div>
                        <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-1">Tax Rate (%)</label>
                        <input type="number" name="tax_rate" id="tax_rate" value="{{ old('tax_rate', 0) }}" step="0.01" min="0" max="100"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>
                </div>
            </div>

            <!-- Inventory -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Inventory</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU (Stock Keeping Unit)</label>
                        <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>
                    
                    <div>
                        <label for="barcode" class="block text-sm font-medium text-gray-700 mb-1">Barcode (ISBN, UPC, etc.)</label>
                        <input type="text" name="barcode" id="barcode" value="{{ old('barcode') }}"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="track_quantity" id="track_quantity" value="1" {{ old('track_quantity', true) ? 'checked' : '' }}
                            class="rounded text-pink-500 focus:ring-pink-500 mr-2" onchange="toggleQuantityFields()">
                        <label for="track_quantity" class="text-sm text-gray-700">Track quantity</label>
                    </div>
                    
                    <div id="quantity-fields" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 0) }}" min="0"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                        </div>
                        
                        <div>
                            <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700 mb-1">Low Stock Alert</label>
                            <input type="number" name="low_stock_threshold" id="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}" min="0"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Shipping</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                        <input type="number" name="weight" id="weight" value="{{ old('weight') }}" step="0.01" min="0"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>
                    
                    <div>
                        <label for="length" class="block text-sm font-medium text-gray-700 mb-1">Length (cm)</label>
                        <input type="number" name="length" id="length" value="{{ old('length') }}" step="0.01" min="0"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>
                    
                    <div>
                        <label for="width" class="block text-sm font-medium text-gray-700 mb-1">Width (cm)</label>
                        <input type="number" name="width" id="width" value="{{ old('width') }}" step="0.01" min="0"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>
                    
                    <div>
                        <label for="height" class="block text-sm font-medium text-gray-700 mb-1">Height (cm)</label>
                        <input type="number" name="height" id="height" value="{{ old('height') }}" step="0.01" min="0"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>
                </div>
            </div>

            <!-- SEO -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">SEO Settings</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>
                    
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="2"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">{{ old('meta_description') }}</textarea>
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
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="rounded text-pink-500 focus:ring-pink-500 mr-2">
                        <label for="is_active" class="text-sm text-gray-700">Active</label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                            class="rounded text-pink-500 focus:ring-pink-500 mr-2">
                        <label for="is_featured" class="text-sm text-gray-700">Featured Product</label>
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
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Featured Image -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Featured Image</h3>
                
                <div class="space-y-4">
                    <div id="featured-preview" class="w-full aspect-square rounded-lg bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300">
                        <div class="text-center">
                            <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-500">No image selected</p>
                        </div>
                    </div>
                    
                    <input type="file" name="featured_image" id="featured_image" accept="image/*" class="hidden" onchange="previewFeaturedImage(this)">
                    <label for="featured_image" class="block w-full text-center py-2 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <i class="fas fa-upload mr-2"></i> Choose Image
                    </label>
                </div>
            </div>

            <!-- Gallery Images -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Gallery Images</h3>
                
                <div class="space-y-4">
                    <div id="gallery-preview" class="grid grid-cols-3 gap-2">
                        <!-- Preview images will appear here -->
                    </div>
                    
                    <input type="file" name="gallery[]" id="gallery" accept="image/*" multiple class="hidden" onchange="previewGallery(this)">
                    <label for="gallery" class="block w-full text-center py-2 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <i class="fas fa-images mr-2"></i> Choose Images
                    </label>
                    <p class="text-xs text-gray-500">You can select multiple images</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-6 flex justify-end space-x-4">
        <a href="{{ route('user.shop.products.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" class="px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
            <i class="fas fa-save mr-2"></i> Save Product
        </button>
    </div>
</form>

@push('scripts')
<script>
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        const slug = this.value.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        document.getElementById('slug').value = slug;
    });

    // Toggle quantity fields
    function toggleQuantityFields() {
        const checkbox = document.getElementById('track_quantity');
        const fields = document.getElementById('quantity-fields');
        fields.style.display = checkbox.checked ? 'grid' : 'none';
    }

    // Preview featured image
    function previewFeaturedImage(input) {
        const preview = document.getElementById('featured-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-lg">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Preview gallery images
    function previewGallery(input) {
        const preview = document.getElementById('gallery-preview');
        preview.innerHTML = '';
        
        if (input.files) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'aspect-square rounded bg-gray-100 overflow-hidden';
                    div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    preview.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }
    }

    // Initialize
    toggleQuantityFields();
</script>
@endpush
@endsection
