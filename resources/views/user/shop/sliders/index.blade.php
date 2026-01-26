@extends('user.shop.layout')

@section('title', 'Sliders - ' . $shop->name)

@section('shop-content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Sliders</h1>
            <p class="text-gray-600">Manage hero sliders for your shop homepage</p>
        </div>
        <button onclick="openSliderModal()"
            class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition flex items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>Add Slider</span>
        </button>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    @if($sliders->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
            @foreach($sliders as $slider)
                <div class="relative group rounded-lg overflow-hidden shadow-md border">
                    <!-- Slider Image -->
                    <div class="aspect-video bg-gray-100">
                        @if($slider->image)
                            <img src="{{ Storage::url($slider->image) }}" alt="{{ $slider->title }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-image text-4xl text-gray-300"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <div class="flex justify-center gap-2">
                                <button onclick="editSlider({{ json_encode($slider) }})"
                                    class="px-3 py-1.5 bg-white text-gray-700 rounded-lg text-sm hover:bg-gray-100 transition">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                                <form action="{{ route('user.shop.sliders.destroy', $slider) }}" method="POST"
                                    onsubmit="return confirm('Delete this slider?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition">
                                        <i class="fas fa-trash mr-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-semibold text-gray-800 truncate">{{ $slider->title ?? 'Untitled' }}</h4>
                            @if($slider->is_active)
                                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs">Active</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">Inactive</span>
                            @endif
                        </div>
                        @if($slider->subtitle)
                            <p class="text-sm text-gray-600 truncate">{{ $slider->subtitle }}</p>
                        @endif
                        <div class="mt-2 text-xs text-gray-500 flex items-center gap-2">
                            <span>Order: {{ $slider->order }}</span>
                            @if($slider->button_text && $slider->button_link)
                                <span class="text-pink-500">• Has CTA</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="px-6 py-4 border-t">
            {{ $sliders->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-images text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-700 mb-2">No sliders yet</h3>
            <p class="text-gray-500 mb-4">Add hero sliders to showcase your products and offers</p>
            <button onclick="openSliderModal()"
                class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition">
                <i class="fas fa-plus mr-2"></i>Add First Slider
            </button>
        </div>
    @endif
</div>

<!-- Slider Modal -->
<div id="sliderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b flex items-center justify-between sticky top-0 bg-white">
            <h3 class="text-lg font-semibold text-gray-800" id="sliderModalTitle">Add Slider</h3>
            <button onclick="closeSliderModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="sliderForm" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <input type="hidden" name="_method" id="sliderFormMethod" value="POST">
            
            <div class="space-y-6">
                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Slider Image <span class="text-red-500">*</span></label>
                    <div id="sliderImagePreview" class="aspect-video bg-gray-100 rounded-lg overflow-hidden mb-3 flex items-center justify-center">
                        <i class="fas fa-image text-4xl text-gray-300"></i>
                    </div>
                    <label class="cursor-pointer bg-white px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition inline-flex items-center">
                        <i class="fas fa-upload mr-2 text-gray-400"></i>
                        <span class="text-sm text-gray-600">Choose Image</span>
                        <input type="file" name="image" id="sliderImage" accept="image/*" class="hidden"
                            onchange="previewSliderImage(this)">
                    </label>
                    <p class="text-xs text-gray-500 mt-2">Recommended: 1920x600px, max 2MB</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="sliderTitle" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" id="sliderTitle"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="Slider title">
                    </div>

                    <div>
                        <label for="sliderSubtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <input type="text" name="subtitle" id="sliderSubtitle"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="Slider subtitle">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="sliderButtonText" class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                        <input type="text" name="button_text" id="sliderButtonText"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="e.g., Shop Now">
                    </div>

                    <div>
                        <label for="sliderButtonLink" class="block text-sm font-medium text-gray-700 mb-1">Button Link</label>
                        <input type="text" name="button_link" id="sliderButtonLink"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                            placeholder="e.g., /products or https://...">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="sliderOrder" class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                        <input type="number" name="order" id="sliderOrder" min="0" value="0"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>

                    <div>
                        <label for="sliderTextColor" class="block text-sm font-medium text-gray-700 mb-1">Text Color</label>
                        <select name="text_color" id="sliderTextColor"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
                            <option value="white">White</option>
                            <option value="black">Black</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="sliderActive" value="1" checked
                            class="rounded text-pink-500 focus:ring-pink-500">
                        <span class="text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeSliderModal()"
                    class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                    <span id="sliderSubmitText">Create Slider</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openSliderModal() {
    const modal = document.getElementById('sliderModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Reset form
    document.getElementById('sliderModalTitle').textContent = 'Add Slider';
    document.getElementById('sliderSubmitText').textContent = 'Create Slider';
    document.getElementById('sliderForm').action = "{{ route('user.shop.sliders.store') }}";
    document.getElementById('sliderFormMethod').value = 'POST';
    document.getElementById('sliderForm').reset();
    document.getElementById('sliderImagePreview').innerHTML = '<i class="fas fa-image text-4xl text-gray-300"></i>';
    document.getElementById('sliderActive').checked = true;
}

function closeSliderModal() {
    const modal = document.getElementById('sliderModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function editSlider(slider) {
    document.getElementById('sliderModalTitle').textContent = 'Edit Slider';
    document.getElementById('sliderSubmitText').textContent = 'Update Slider';
    document.getElementById('sliderForm').action = `/user/shop/sliders/${slider.id}`;
    document.getElementById('sliderFormMethod').value = 'PUT';
    
    document.getElementById('sliderTitle').value = slider.title || '';
    document.getElementById('sliderSubtitle').value = slider.subtitle || '';
    document.getElementById('sliderButtonText').value = slider.button_text || '';
    document.getElementById('sliderButtonLink').value = slider.button_link || '';
    document.getElementById('sliderOrder').value = slider.order || 0;
    document.getElementById('sliderTextColor').value = slider.text_color || 'white';
    document.getElementById('sliderActive').checked = slider.is_active;
    
    if (slider.image) {
        document.getElementById('sliderImagePreview').innerHTML = `<img src="/storage/${slider.image}" class="w-full h-full object-cover">`;
    }
    
    openSliderModal();
}

function previewSliderImage(input) {
    const preview = document.getElementById('sliderImagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Close modal on outside click
document.getElementById('sliderModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSliderModal();
    }
});
</script>
@endpush
@endsection
