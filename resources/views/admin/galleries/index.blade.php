@extends('layouts.admin')

@section('title', 'Gallery')
@section('page-title', 'Gallery')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <p class="text-gray-600">Manage your photo gallery and images.</p>
    <a href="{{ route('admin.galleries.create') }}" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition">
        <i class="fas fa-plus mr-2"></i>Add Image
    </a>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
</div>
@endif

<!-- Filter by category -->
@php
    $categories = $galleries->pluck('category')->filter()->unique()->values();
@endphp
@if($categories->count() > 0)
<div class="mb-6 flex flex-wrap gap-2">
    <span class="px-3 py-1 bg-rose-600 text-white rounded-full text-sm cursor-pointer">All</span>
    @foreach($categories as $category)
    <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm cursor-pointer hover:bg-rose-100 hover:text-rose-600 transition">{{ $category }}</span>
    @endforeach
</div>
@endif

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @forelse($galleries as $gallery)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden group">
        <div class="relative aspect-square">
            <img src="{{ Str::startsWith($gallery->image, 'http') ? $gallery->image : Storage::url($gallery->image) }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
                <div class="flex items-center space-x-2">
                    <button type="button" class="p-2 bg-white text-gray-700 rounded-full hover:bg-gray-100 transition" onclick="showPreview('{{ Str::startsWith($gallery->image, 'http') ? $gallery->image : Storage::url($gallery->image) }}', '{{ $gallery->title }}')">
                        <i class="fas fa-expand"></i>
                    </button>
                    <a href="{{ route('admin.galleries.edit', $gallery) }}" class="p-2 bg-white text-blue-600 rounded-full hover:bg-blue-50 transition">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 bg-white text-red-600 rounded-full hover:bg-red-50 transition">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @if(!$gallery->is_active)
            <div class="absolute top-2 right-2">
                <span class="px-2 py-1 text-xs font-medium bg-gray-500 text-white rounded-full">Hidden</span>
            </div>
            @endif
        </div>
        <div class="p-3">
            <h3 class="font-medium text-gray-900 text-sm truncate">{{ $gallery->title }}</h3>
            @if($gallery->category)
            <span class="text-xs text-rose-600">{{ $gallery->category }}</span>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <i class="fas fa-images text-4xl text-gray-300 mb-4"></i>
        <p class="text-gray-500 mb-4">No images in gallery yet.</p>
        <a href="{{ route('admin.galleries.create') }}" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition">
            <i class="fas fa-plus mr-2"></i>Add First Image
        </a>
    </div>
    @endforelse
</div>

@if($galleries->hasPages())
<div class="mt-6">
    {{ $galleries->links() }}
</div>
@endif

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-90">
    <button onclick="closePreview()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">
        <i class="fas fa-times"></i>
    </button>
    <img id="previewImage" src="" alt="" class="max-w-full max-h-[90vh] object-contain">
    <p id="previewTitle" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white text-lg"></p>
</div>

<script>
    function showPreview(src, title) {
        document.getElementById('previewImage').src = src;
        document.getElementById('previewTitle').textContent = title;
        document.getElementById('previewModal').classList.remove('hidden');
        document.getElementById('previewModal').classList.add('flex');
    }
    
    function closePreview() {
        document.getElementById('previewModal').classList.add('hidden');
        document.getElementById('previewModal').classList.remove('flex');
    }
    
    document.getElementById('previewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePreview();
        }
    });
</script>
@endsection
