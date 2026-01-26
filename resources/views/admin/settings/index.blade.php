@extends('layouts.admin')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="max-w-4xl">
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- General Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-globe text-rose-500 mr-2"></i>General Settings
            </h3>
            
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Site Name *</label>
                        <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $settings['site_name'] ?? 'VisionSphere – Explore your world of ideas and stories.') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent" required>
                        @error('site_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="site_tagline" class="block text-sm font-medium text-gray-700 mb-1">Site Tagline</label>
                        <input type="text" name="site_tagline" id="site_tagline" value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                            placeholder="A short tagline for your blog">
                    </div>
                </div>
                
                <div>
                    <label for="site_description" class="block text-sm font-medium text-gray-700 mb-1">Site Description</label>
                    <textarea name="site_description" id="site_description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        placeholder="Describe your blog...">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                </div>
                
                <div>
                    <label for="posts_per_page" class="block text-sm font-medium text-gray-700 mb-1">Posts Per Page</label>
                    <input type="number" name="posts_per_page" id="posts_per_page" value="{{ old('posts_per_page', $settings['posts_per_page'] ?? 10) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        min="1" max="50">
                </div>
            </div>
        </div>

        <!-- Brand Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-image text-rose-500 mr-2"></i>Brand Settings
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Site Logo</label>
                    @if(isset($settingsArray['site_logo']) && $settingsArray['site_logo'])
                    <div class="mb-2 p-2 bg-gray-100 rounded-lg inline-block">
                        <img src="{{ Storage::url($settingsArray['site_logo']) }}" alt="Logo" class="h-16 object-contain">
                    </div>
                    @endif
                    <input type="file" name="site_logo" accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Recommended: 200x60px (PNG, SVG preferred)</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Site Favicon</label>
                    @if(isset($settingsArray['site_favicon']) && $settingsArray['site_favicon'])
                    <div class="mb-2 p-2 bg-gray-100 rounded-lg inline-block">
                        <img src="{{ Storage::url($settingsArray['site_favicon']) }}" alt="Favicon" class="h-8 w-8 object-contain">
                    </div>
                    @endif
                    <input type="file" name="site_favicon" accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Recommended: 32x32px (ICO, PNG preferred)</p>
                </div>
            </div>
        </div>

        <!-- Contact Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-address-card text-rose-500 mr-2"></i>Contact Information
            </h3>
            
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                            placeholder="contact@example.com">
                    </div>
                    
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                            placeholder="+1 234 567 890">
                    </div>
                </div>
                
                <div>
                    <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="contact_address" id="contact_address" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        placeholder="123 Main Street, City, Country">{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-share-alt text-rose-500 mr-2"></i>Social Media Links
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="facebook_url" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fab fa-facebook text-blue-600 mr-1"></i>Facebook
                    </label>
                    <input type="url" name="facebook_url" id="facebook_url" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        placeholder="https://facebook.com/yourpage">
                </div>
                
                <div>
                    <label for="twitter_url" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fab fa-twitter text-blue-400 mr-1"></i>Twitter
                    </label>
                    <input type="url" name="twitter_url" id="twitter_url" value="{{ old('twitter_url', $settings['twitter_url'] ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        placeholder="https://twitter.com/yourhandle">
                </div>
                
                <div>
                    <label for="instagram_url" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fab fa-instagram text-pink-600 mr-1"></i>Instagram
                    </label>
                    <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        placeholder="https://instagram.com/yourprofile">
                </div>
                
                <div>
                    <label for="youtube_url" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fab fa-youtube text-red-600 mr-1"></i>YouTube
                    </label>
                    <input type="url" name="youtube_url" id="youtube_url" value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        placeholder="https://youtube.com/yourchannel">
                </div>
            </div>
        </div>

        <!-- Footer & Analytics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-code text-rose-500 mr-2"></i>Footer & Analytics
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label for="footer_text" class="block text-sm font-medium text-gray-700 mb-1">Footer Text</label>
                    <textarea name="footer_text" id="footer_text" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        placeholder="© 2024 Your Blog. All rights reserved.">{{ old('footer_text', $settings['footer_text'] ?? '') }}</textarea>
                </div>
                
                <div>
                    <label for="google_analytics" class="block text-sm font-medium text-gray-700 mb-1">Google Analytics ID</label>
                    <input type="text" name="google_analytics" id="google_analytics" value="{{ old('google_analytics', $settings['google_analytics'] ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        placeholder="G-XXXXXXXXXX">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition font-medium">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </form>
</div>
@endsection