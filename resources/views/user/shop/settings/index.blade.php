@extends('user.shop.layout')

@section('title', 'Settings - ' . $shop->name)

@section('shop-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Shop Settings</h1>
    <p class="text-gray-600">Manage your shop information and preferences</p>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
@endif

<form action="{{ route('user.shop.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="space-y-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-store text-pink-500"></i> Basic Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Logo -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Shop Logo</label>
                    <div class="flex items-center gap-4">
                        <div id="logo-preview" class="w-24 h-24 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden">
                            @if($shop->logo)
                                <img src="{{ Storage::url($shop->logo) }}" alt="Logo" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-store text-3xl text-gray-400"></i>
                            @endif
                        </div>
                        <div>
                            <label class="cursor-pointer bg-white px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition inline-block">
                                <span class="text-sm text-gray-600"><i class="fas fa-upload mr-2"></i>Change Logo</span>
                                <input type="file" name="logo" accept="image/*" class="hidden" onchange="previewImage(this, 'logo-preview')">
                            </label>
                            <p class="text-xs text-gray-500 mt-2">Recommended: 200x200px, max 2MB</p>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Shop Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $shop->name) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Shop Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="Tell customers about your shop">{{ old('description', $shop->description) }}</textarea>
                </div>

                <div>
                    <label for="tagline" class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                    <input type="text" name="tagline" id="tagline" value="{{ old('tagline', $shop->tagline) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="Short catchy phrase">
                </div>

                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                    <select name="currency" id="currency"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                        <option value="PKR" {{ old('currency', $shop->currency) === 'PKR' ? 'selected' : '' }}>PKR - Pakistani Rupee</option>
                        <option value="USD" {{ old('currency', $shop->currency) === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                        <option value="EUR" {{ old('currency', $shop->currency) === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                        <option value="GBP" {{ old('currency', $shop->currency) === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                        <option value="AED" {{ old('currency', $shop->currency) === 'AED' ? 'selected' : '' }}>AED - UAE Dirham</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-address-book text-pink-500"></i> Contact Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Business Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $shop->email) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="shop@example.com">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $shop->phone) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="+92 300 1234567">
                </div>

                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-1">
                        WhatsApp Number <span class="text-green-500"><i class="fab fa-whatsapp"></i></span>
                    </label>
                    <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $shop->whatsapp) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="923001234567 (without + sign)">
                    <p class="text-xs text-gray-500 mt-1">This will be used for the WhatsApp chat widget</p>
                </div>

                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                    <input type="url" name="website" id="website" value="{{ old('website', $shop->website) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="https://example.com">
                </div>
            </div>
        </div>

        <!-- Address -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-pink-500"></i> Business Address
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Street Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $shop->address) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="Street address">
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city', $shop->city) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                </div>

                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                    <input type="text" name="state" id="state" value="{{ old('state', $shop->state) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                </div>

                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                    <input type="text" name="country" id="country" value="{{ old('country', $shop->country) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                </div>

                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $shop->postal_code) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-share-alt text-pink-500"></i> Social Media Links
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="facebook" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fab fa-facebook text-blue-600 mr-1"></i> Facebook
                    </label>
                    <input type="url" name="social_links[facebook]" id="facebook" 
                        value="{{ old('social_links.facebook', $shop->social_links['facebook'] ?? '') }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="https://facebook.com/yourpage">
                </div>

                <div>
                    <label for="instagram" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fab fa-instagram text-pink-600 mr-1"></i> Instagram
                    </label>
                    <input type="url" name="social_links[instagram]" id="instagram" 
                        value="{{ old('social_links.instagram', $shop->social_links['instagram'] ?? '') }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="https://instagram.com/yourpage">
                </div>

                <div>
                    <label for="twitter" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fab fa-twitter text-blue-400 mr-1"></i> Twitter
                    </label>
                    <input type="url" name="social_links[twitter]" id="twitter" 
                        value="{{ old('social_links.twitter', $shop->social_links['twitter'] ?? '') }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="https://twitter.com/yourpage">
                </div>

                <div>
                    <label for="youtube" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fab fa-youtube text-red-600 mr-1"></i> YouTube
                    </label>
                    <input type="url" name="social_links[youtube]" id="youtube" 
                        value="{{ old('social_links.youtube', $shop->social_links['youtube'] ?? '') }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="https://youtube.com/yourchannel">
                </div>

                <div>
                    <label for="tiktok" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fab fa-tiktok mr-1"></i> TikTok
                    </label>
                    <input type="url" name="social_links[tiktok]" id="tiktok" 
                        value="{{ old('social_links.tiktok', $shop->social_links['tiktok'] ?? '') }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="https://tiktok.com/@yourpage">
                </div>
            </div>
        </div>

        <!-- Tax & Shipping Settings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-truck text-pink-500"></i> Tax & Shipping
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-1">Default Tax Rate (%)</label>
                    <input type="number" name="tax_rate" id="tax_rate" step="0.01" min="0" max="100"
                        value="{{ old('tax_rate', $shop->tax_rate) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="e.g., 17">
                </div>

                <div>
                    <label for="shipping_fee" class="block text-sm font-medium text-gray-700 mb-1">Default Shipping Fee</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-500">{{ $shop->currency }}</span>
                        <input type="number" name="shipping_fee" id="shipping_fee" step="0.01" min="0"
                            value="{{ old('shipping_fee', $shop->shipping_fee) }}"
                            class="w-full border rounded-lg pl-14 pr-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="0.00">
                    </div>
                </div>

                <div>
                    <label for="free_shipping_threshold" class="block text-sm font-medium text-gray-700 mb-1">Free Shipping Above</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-500">{{ $shop->currency }}</span>
                        <input type="number" name="free_shipping_threshold" id="free_shipping_threshold" step="0.01" min="0"
                            value="{{ old('free_shipping_threshold', $shop->free_shipping_threshold) }}"
                            class="w-full border rounded-lg pl-14 pr-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="Leave empty to disable">
                    </div>
                </div>

                <div>
                    <label for="minimum_order" class="block text-sm font-medium text-gray-700 mb-1">Minimum Order Amount</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-500">{{ $shop->currency }}</span>
                        <input type="number" name="minimum_order" id="minimum_order" step="0.01" min="0"
                            value="{{ old('minimum_order', $shop->minimum_order) }}"
                            class="w-full border rounded-lg pl-14 pr-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="Leave empty to disable">
                    </div>
                </div>
            </div>
        </div>

        <!-- Store Appearance -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-paint-brush text-pink-500"></i> Store Appearance
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-1">Primary Color</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="primary_color" id="primary_color"
                            value="{{ old('primary_color', $shop->primary_color ?? '#ec4899') }}"
                            class="w-12 h-10 rounded border cursor-pointer">
                        <input type="text" value="{{ old('primary_color', $shop->primary_color ?? '#ec4899') }}"
                            class="flex-1 border rounded-lg px-4 py-2" readonly>
                    </div>
                </div>

                <div>
                    <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-1">Secondary Color</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="secondary_color" id="secondary_color"
                            value="{{ old('secondary_color', $shop->secondary_color ?? '#8b5cf6') }}"
                            class="w-12 h-10 rounded border cursor-pointer">
                        <input type="text" value="{{ old('secondary_color', $shop->secondary_color ?? '#8b5cf6') }}"
                            class="flex-1 border rounded-lg px-4 py-2" readonly>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Banner Image</label>
                    <label class="cursor-pointer bg-white px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition inline-flex items-center">
                        <i class="fas fa-upload mr-2 text-gray-400"></i>
                        <span class="text-sm text-gray-600">Upload Banner</span>
                        <input type="file" name="banner" accept="image/*" class="hidden">
                    </label>
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-search text-pink-500"></i> SEO Settings
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" 
                        value="{{ old('meta_title', $shop->meta_title) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="SEO title (leave empty to use shop name)">
                </div>

                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" rows="3"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="SEO description for search engines">{{ old('meta_description', $shop->meta_description) }}</textarea>
                </div>

                <div>
                    <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                    <input type="text" name="meta_keywords" id="meta_keywords" 
                        value="{{ old('meta_keywords', $shop->meta_keywords) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                        placeholder="keyword1, keyword2, keyword3">
                </div>
            </div>
        </div>

        <!-- Shop URL -->
        <div class="bg-gradient-to-r from-pink-50 to-purple-50 rounded-lg shadow p-6 border border-pink-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-link text-pink-500"></i> Your Shop URL
            </h3>
            
            <div class="flex items-center gap-4">
                <div class="flex-1 bg-white rounded-lg px-4 py-3 border">
                    <span class="text-gray-500">{{ url('/shop/') }}/</span>
                    <span class="font-medium text-pink-600">{{ $shop->slug }}</span>
                </div>
                <a href="{{ route('shop.show', $shop->slug) }}" target="_blank"
                    class="px-4 py-3 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                    <i class="fas fa-external-link-alt mr-2"></i>Visit Shop
                </a>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end">
            <button type="submit"
                class="px-8 py-3 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
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

// Sync color picker with text input
document.querySelectorAll('input[type="color"]').forEach(colorInput => {
    colorInput.addEventListener('input', function() {
        this.nextElementSibling.value = this.value;
    });
});
</script>
@endpush
@endsection
