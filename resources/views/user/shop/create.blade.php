@extends('layouts.app')

@section('title', 'Create Your Shop')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 to-purple-100 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Create Your Online Shop</h1>
                <p class="text-gray-600 text-lg">Start selling online with a 30-day free trial. No credit card required.</p>
            </div>

            <!-- Trial Benefits -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-gift text-pink-500"></i> 30-Day Free Trial Includes
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-3 bg-pink-50 rounded-lg">
                        <i class="fas fa-box text-2xl text-pink-500 mb-2"></i>
                        <p class="text-sm font-medium">Up to 10 Products</p>
                    </div>
                    <div class="text-center p-3 bg-purple-50 rounded-lg">
                        <i class="fas fa-shopping-cart text-2xl text-purple-500 mb-2"></i>
                        <p class="text-sm font-medium">Unlimited Orders</p>
                    </div>
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <i class="fab fa-whatsapp text-2xl text-green-500 mb-2"></i>
                        <p class="text-sm font-medium">WhatsApp Orders</p>
                    </div>
                    <div class="text-center p-3 bg-yellow-50 rounded-lg">
                        <i class="fas fa-headset text-2xl text-yellow-500 mb-2"></i>
                        <p class="text-sm font-medium">Full Support</p>
                    </div>
                </div>
            </div>

            <!-- Shop Creation Form -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white px-6 py-4">
                    <h2 class="text-xl font-semibold">Shop Information</h2>
                </div>
                
                <form action="{{ route('user.shop.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Shop Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Shop Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                placeholder="Enter your shop name">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Shop Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                placeholder="Tell customers about your shop">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Logo -->
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Shop Logo</label>
                            <div class="mt-1 flex items-center gap-4">
                                <div id="logo-preview" class="w-20 h-20 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden">
                                    <i class="fas fa-store text-3xl text-gray-400"></i>
                                </div>
                                <label class="cursor-pointer bg-white px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                    <span class="text-sm text-gray-600">Choose file</span>
                                    <input type="file" name="logo" id="logo" accept="image/*" class="hidden"
                                        onchange="previewImage(this, 'logo-preview')">
                                </label>
                            </div>
                            @error('logo')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Currency -->
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                            <select name="currency" id="currency"
                                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                <option value="PKR" {{ old('currency') === 'PKR' ? 'selected' : '' }}>PKR - Pakistani Rupee</option>
                                <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                <option value="GBP" {{ old('currency') === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                <option value="AED" {{ old('currency') === 'AED' ? 'selected' : '' }}>AED - UAE Dirham</option>
                            </select>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Business Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                placeholder="shop@example.com">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                                placeholder="+92 300 1234567">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-1">
                                WhatsApp Number <span class="text-green-500"><i class="fab fa-whatsapp"></i></span>
                            </label>
                            <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}"
                                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('whatsapp') border-red-500 @enderror"
                                placeholder="923001234567 (without + sign)">
                            <p class="text-xs text-gray-500 mt-1">Customers can contact you via WhatsApp for orders</p>
                            @error('whatsapp')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Business Address</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}"
                                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('address') border-red-500 @enderror"
                                placeholder="Street address">
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}"
                                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('city') border-red-500 @enderror"
                                placeholder="City">
                            @error('city')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- State -->
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                            <input type="text" name="state" id="state" value="{{ old('state') }}"
                                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('state') border-red-500 @enderror"
                                placeholder="State/Province">
                            @error('state')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <input type="text" name="country" id="country" value="{{ old('country', 'Pakistan') }}"
                                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('country') border-red-500 @enderror"
                                placeholder="Country">
                            @error('country')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Postal Code -->
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}"
                                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('postal_code') border-red-500 @enderror"
                                placeholder="Postal code">
                            @error('postal_code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" name="terms" id="terms" required
                                class="mt-1 rounded text-pink-500 focus:ring-pink-500">
                            <label for="terms" class="text-sm text-gray-600">
                                I agree to the <a href="#" class="text-pink-500 hover:underline">Terms of Service</a> 
                                and <a href="#" class="text-pink-500 hover:underline">Privacy Policy</a>. 
                                I understand that my shop will be reviewed before activation.
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex items-center justify-between">
                        <a href="{{ route('user.dashboard') }}" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                        </a>
                        <button type="submit"
                            class="bg-gradient-to-r from-pink-500 to-purple-600 text-white px-8 py-3 rounded-lg font-semibold hover:from-pink-600 hover:to-purple-700 transition shadow-lg">
                            <i class="fas fa-rocket mr-2"></i> Start Free Trial
                        </button>
                    </div>
                </form>
            </div>

            <!-- Pricing Preview -->
            <div class="mt-8 text-center">
                <p class="text-gray-600 mb-4">After your free trial, upgrade to unlock more features</p>
                <a href="{{ route('shops.index') }}" class="text-pink-500 hover:text-pink-600 font-medium">
                    View Pricing Plans <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

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
</script>
@endpush
@endsection
