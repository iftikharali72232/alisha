@extends('user.shop.layout')

@section('title', 'Create Category - ' . $shop->name)

@section('shop-content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Create Category</h1>
            <p class="text-gray-600">Add a new category to organize your products</p>
        </div>
        <a href="{{ route('user.shop.categories.index') }}"
            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Categories
        </a>
    </div>
</div>

@if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('user.shop.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Category Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">Parent Category</label>
                <select name="parent_id" id="parent_id"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                    <option value="">Select parent category (optional)</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" id="description" rows="4"
                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                placeholder="Optional description">{{ old('description') }}</textarea>
        </div>

        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Category Image</label>
            <input type="file" name="image" id="image" accept="image/*"
                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
            <p class="text-gray-500 text-sm mt-1">Optional. Recommended size: 400x400px</p>
        </div>

        <div class="mt-6 flex items-center">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
            <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
        </div>

        <div class="mt-6 flex justify-end gap-4">
            <a href="{{ route('user.shop.categories.index') }}"
                class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                Cancel
            </a>
            <button type="submit"
                class="bg-pink-500 text-white px-6 py-2 rounded-lg hover:bg-pink-600 transition">
                <i class="fas fa-save mr-2"></i>Create Category
            </button>
        </div>
    </form>
</div>
@endsection