@extends('layouts.admin')

@section('title', 'Edit Shop: ' . $shop->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.shops.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Shop</h1>
            <p class="text-gray-600">Update shop information and settings</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.shops.update', $shop) }}" method="POST" enctype="multipart/form-data" class="max-w-4xl">
        @csrf
        @method('PATCH')

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Shop Owner</h2>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Select User *</label>
                <select name="user_id" class="w-full border rounded-lg px-3 py-2 @error('user_id') border-red-500 @enderror" required>
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $shop->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Shop Information</h2>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Shop Name *</label>
                    <input type="text" name="name" value="{{ old('name', $shop->name) }}"
                        class="w-full border rounded-lg px-3 py-2 @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $shop->slug) }}"
                        class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2">{{ old('description', $shop->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                    @if($shop->logo)
                        <div class="mb-2">
                            <img src="{{ Storage::url($shop->logo) }}" alt="Current Logo" class="w-20 h-20 object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="logo" accept="image/*" class="w-full border rounded-lg px-3 py-2">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current logo</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Banner</label>
                    @if($shop->banner)
                        <div class="mb-2">
                            <img src="{{ Storage::url($shop->banner) }}" alt="Current Banner" class="w-32 h-20 object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="banner" accept="image/*" class="w-full border rounded-lg px-3 py-2">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current banner</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Contact Information</h2>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $shop->email) }}"
                        class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $shop->phone) }}"
                        class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $shop->whatsapp) }}"
                        class="w-full border rounded-lg px-3 py-2" placeholder="+923001234567">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Theme Color</label>
                    <input type="color" name="theme_color" value="{{ old('theme_color', $shop->theme_color ?? '#ec4899') }}"
                        class="w-full h-10 border rounded-lg">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea name="address" rows="2" class="w-full border rounded-lg px-3 py-2">{{ old('address', $shop->address) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city', $shop->city) }}"
                        class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                    <input type="text" name="country" value="{{ old('country', $shop->country ?? 'Pakistan') }}"
                        class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Subscription</h2>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Current Plan</label>
                <div class="bg-gray-50 p-3 rounded-lg">
                    @if($shop->activeSubscription)
                        <p class="font-medium">{{ $shop->activeSubscription->plan->name }}</p>
                        <p class="text-sm text-gray-600">
                            Status: <span class="capitalize">{{ $shop->activeSubscription->status }}</span>
                            @if($shop->activeSubscription->ends_at)
                                | Ends: {{ $shop->activeSubscription->ends_at->format('M d, Y') }}
                            @endif
                        </p>
                    @else
                        <p class="text-gray-500">No active subscription</p>
                    @endif
                </div>
            </div>

            <div class="text-sm text-gray-600">
                <p><strong>Note:</strong> To change subscription plans, use the "Manage Subscription" button from the shop details page.</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Status & SEO</h2>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="active" {{ old('status', $shop->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $shop->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="pending" {{ old('status', $shop->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $shop->meta_title) }}"
                        class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                <textarea name="meta_description" rows="2" class="w-full border rounded-lg px-3 py-2">{{ old('meta_description', $shop->meta_description) }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.shops.show', $shop) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Cancel
            </a>
            <button type="submit" class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700">
                <i class="fas fa-save mr-2"></i> Update Shop
            </button>
        </div>
    </form>
</div>
@endsection