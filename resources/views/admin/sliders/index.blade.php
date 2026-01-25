@extends('layouts.admin')

@section('title', 'Sliders')
@section('page-title', 'Sliders')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <p class="text-gray-600">Manage homepage slider images and banners.</p>
    <a href="{{ route('admin.sliders.create') }}" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition">
        <i class="fas fa-plus mr-2"></i>Add Slider
    </a>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($sliders as $slider)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden group">
        <div class="relative aspect-video">
            <img src="{{ Storage::url($slider->image) }}" alt="{{ $slider->title }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
                <div class="flex items-center space-x-2">
                    <form action="{{ route('admin.sliders.toggle-active', $slider) }}" method="POST" class="inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="p-2 bg-white text-green-600 rounded-full hover:bg-green-50 transition" title="{{ $slider->is_active ? 'Deactivate' : 'Activate' }}">
                            <i class="fas {{ $slider->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                        </button>
                    </form>
                    <a href="{{ route('admin.sliders.edit', $slider) }}" class="p-2 bg-white text-blue-600 rounded-full hover:bg-blue-50 transition">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 bg-white text-red-600 rounded-full hover:bg-red-50 transition">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="absolute top-2 left-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-black bg-opacity-50 text-white text-sm font-medium">
                    {{ $slider->order }}
                </span>
            </div>
            <div class="absolute top-2 right-2">
                @if($slider->is_active)
                <span class="px-2 py-1 text-xs font-medium bg-green-500 text-white rounded-full">Active</span>
                @else
                <span class="px-2 py-1 text-xs font-medium bg-gray-500 text-white rounded-full">Inactive</span>
                @endif
            </div>
        </div>
        <div class="p-4">
            <h3 class="font-semibold text-gray-900 mb-1">{{ $slider->title }}</h3>
            @if($slider->description)
            <p class="text-sm text-gray-600 line-clamp-2">{{ $slider->description }}</p>
            @endif
            @if($slider->button_text)
            <div class="mt-2">
                <span class="inline-flex items-center px-2 py-1 text-xs bg-rose-100 text-rose-700 rounded">
                    <i class="fas fa-link mr-1"></i>{{ $slider->button_text }}
                </span>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <i class="fas fa-images text-4xl text-gray-300 mb-4"></i>
        <p class="text-gray-500 mb-4">No sliders added yet.</p>
        <a href="{{ route('admin.sliders.create') }}" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition">
            <i class="fas fa-plus mr-2"></i>Add First Slider
        </a>
    </div>
    @endforelse
</div>

@if($sliders->hasPages())
<div class="mt-6">
    {{ $sliders->links() }}
</div>
@endif
@endsection
