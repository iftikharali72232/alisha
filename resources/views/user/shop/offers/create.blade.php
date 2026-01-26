@extends('user.shop.layout')

@section('title', 'Create Offer')
@section('page-title', 'Create Offer')

@section('shop-content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Create New Offer</h1>
            <p class="text-gray-600">Create a time-limited promotion for your products</p>
        </div>
        <a href="{{ route('user.shop.offers.index') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Offers
        </a>
    </div>
</div>

<form action="{{ route('user.shop.offers.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Offer Details</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Offer Title *</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('title') border-red-500 @enderror"
                            placeholder="e.g., Summer Sale 50% OFF">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="Describe your offer...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Discount -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Discount Settings</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="discount_type" class="block text-sm font-medium text-gray-700 mb-1">Discount Type *</label>
                        <select name="discount_type" id="discount_type" required
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                            <option value="percentage" {{ old('discount_type', 'percentage') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Fixed Amount (Rs.)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-1">Discount Value *</label>
                        <input type="number" name="discount_value" id="discount_value" value="{{ old('discount_value') }}" required step="0.01" min="0"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('discount_value') border-red-500 @enderror">
                        @error('discount_value')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Duration -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Offer Duration</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                        <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at') }}" required
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('starts_at') border-red-500 @enderror">
                        @error('starts_at')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date *</label>
                        <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Quick Duration Buttons -->
                <div class="mt-4">
                    <p class="text-sm text-gray-600 mb-2">Quick set duration:</p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="setDuration(1)" class="px-3 py-1 text-sm border rounded-lg hover:bg-gray-50">1 Day</button>
                        <button type="button" onclick="setDuration(3)" class="px-3 py-1 text-sm border rounded-lg hover:bg-gray-50">3 Days</button>
                        <button type="button" onclick="setDuration(7)" class="px-3 py-1 text-sm border rounded-lg hover:bg-gray-50">1 Week</button>
                        <button type="button" onclick="setDuration(14)" class="px-3 py-1 text-sm border rounded-lg hover:bg-gray-50">2 Weeks</button>
                        <button type="button" onclick="setDuration(30)" class="px-3 py-1 text-sm border rounded-lg hover:bg-gray-50">1 Month</button>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Apply to Products</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="apply_to" value="all" {{ old('apply_to', 'all') === 'all' ? 'checked' : '' }}
                                class="text-pink-500 focus:ring-pink-500" onchange="toggleProductSelection()">
                            <span class="ml-2 text-sm text-gray-700">All Products</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="apply_to" value="selected" {{ old('apply_to') === 'selected' ? 'checked' : '' }}
                                class="text-pink-500 focus:ring-pink-500" onchange="toggleProductSelection()">
                            <span class="ml-2 text-sm text-gray-700">Selected Products</span>
                        </label>
                    </div>
                    
                    <div id="product-selection" class="hidden border rounded-lg p-4 max-h-60 overflow-y-auto">
                        @foreach($products as $product)
                            <label class="flex items-center py-2 border-b last:border-0">
                                <input type="checkbox" name="products[]" value="{{ $product->id }}" 
                                    {{ in_array($product->id, old('products', [])) ? 'checked' : '' }}
                                    class="rounded text-pink-500 focus:ring-pink-500">
                                <span class="ml-3 text-sm text-gray-700">{{ $product->name }}</span>
                                <span class="ml-auto text-sm text-gray-500">Rs. {{ number_format($product->price) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Offer Image -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Offer Banner</h3>
                
                <div class="space-y-4">
                    <div id="image-preview" class="w-full aspect-video rounded-lg bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300">
                        <div class="text-center">
                            <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-500">Upload banner image</p>
                        </div>
                    </div>
                    
                    <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                    <label for="image" class="block w-full text-center py-2 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <i class="fas fa-upload mr-2"></i> Choose Image
                    </label>
                    <p class="text-xs text-gray-500">Recommended: 1200x600 pixels</p>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Status</h3>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="rounded text-pink-500 focus:ring-pink-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="bg-gradient-to-br from-orange-400 to-pink-500 rounded-lg shadow p-6 text-white">
                <h4 class="font-semibold mb-2">Offer Preview</h4>
                <div class="bg-white/20 backdrop-blur rounded-lg p-4">
                    <p id="preview-title" class="text-xl font-bold">Your Offer Title</p>
                    <p id="preview-discount" class="text-3xl font-bold my-2">0% OFF</p>
                    <p id="preview-duration" class="text-sm opacity-80">Duration: --</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-6 flex justify-end space-x-4">
        <a href="{{ route('user.shop.offers.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" class="px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
            <i class="fas fa-save mr-2"></i> Create Offer
        </button>
    </div>
</form>

@push('scripts')
<script>
    function toggleProductSelection() {
        const selected = document.querySelector('input[name="apply_to"]:checked').value;
        const productSelection = document.getElementById('product-selection');
        productSelection.classList.toggle('hidden', selected === 'all');
    }

    function setDuration(days) {
        const now = new Date();
        const startDate = now.toISOString().slice(0, 16);
        const endDate = new Date(now.getTime() + days * 24 * 60 * 60 * 1000).toISOString().slice(0, 16);
        
        document.getElementById('starts_at').value = startDate;
        document.getElementById('end_date').value = endDate;
        updatePreview();
    }

    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-lg">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updatePreview() {
        const title = document.getElementById('title').value || 'Your Offer Title';
        const discountType = document.getElementById('discount_type').value;
        const discountValue = document.getElementById('discount_value').value || 0;
        const startDate = document.getElementById('starts_at').value;
        const endDate = document.getElementById('end_date').value;

        document.getElementById('preview-title').textContent = title;
        document.getElementById('preview-discount').textContent = discountType === 'percentage' 
            ? `${discountValue}% OFF` 
            : `Rs. ${discountValue} OFF`;
        
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
            document.getElementById('preview-duration').textContent = `Duration: ${days} days`;
        }
    }

    // Initialize
    toggleProductSelection();
    
    // Live preview updates
    document.getElementById('title').addEventListener('input', updatePreview);
    document.getElementById('discount_type').addEventListener('change', updatePreview);
    document.getElementById('discount_value').addEventListener('input', updatePreview);
    document.getElementById('starts_at').addEventListener('change', updatePreview);
    document.getElementById('end_date').addEventListener('change', updatePreview);
</script>
@endpush
@endsection
