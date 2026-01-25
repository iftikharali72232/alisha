@extends('layouts.blog')

@section('title', 'Gallery')

@section('content')
    <!-- Gallery Header -->
    <div class="bg-gradient-to-r from-purple-500 to-pink-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Photo Gallery</h1>
            <p class="text-white/80 max-w-2xl mx-auto">Browse through our collection of beautiful moments and memories.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Category Filter -->
        <div class="flex flex-wrap justify-center gap-2 mb-10">
            <a href="{{ route('blog.gallery') }}" class="px-4 py-2 rounded-full text-sm font-medium transition {{ !request('category') ? 'bg-rose-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                All
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('blog.gallery', ['category' => $cat]) }}" class="px-4 py-2 rounded-full text-sm font-medium transition {{ request('category') === $cat ? 'bg-rose-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                {{ $cat }}
            </a>
            @endforeach
        </div>

        @if($galleries->count() > 0)
        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($galleries as $gallery)
            <div class="group relative overflow-hidden rounded-2xl cursor-pointer" onclick="openLightbox('{{ asset('storage/' . $gallery->image) }}', '{{ $gallery->title }}')" style="padding-bottom: 100%;">
                <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                <div class="absolute bottom-0 left-0 right-0 p-4 transform translate-y-full group-hover:translate-y-0 transition duration-300">
                    <h3 class="text-white font-medium">{{ $gallery->title }}</h3>
                    @if($gallery->category)
                    <span class="text-white/70 text-sm">{{ $gallery->category }}</span>
                    @endif
                </div>
                <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition duration-300">
                    <span class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-expand"></i>
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $galleries->links() }}
        </div>
        @else
        <div class="bg-white rounded-2xl p-12 text-center">
            <i class="fas fa-images text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">No images in the gallery yet.</p>
        </div>
        @endif
    </div>

    <!-- Lightbox Modal -->
    <div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4">
        <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300 transition">
            <i class="fas fa-times"></i>
        </button>
        <button onclick="prevImage()" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-2xl hover:text-gray-300 transition">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button onclick="nextImage()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-2xl hover:text-gray-300 transition">
            <i class="fas fa-chevron-right"></i>
        </button>
        <div class="max-w-5xl max-h-full">
            <img id="lightbox-image" src="" alt="" class="max-w-full max-h-[80vh] object-contain rounded-lg">
            <p id="lightbox-title" class="text-white text-center mt-4 text-lg"></p>
        </div>
    </div>
@endsection

@section('scripts')
<script>
const galleryImages = [
    @foreach($galleries as $gallery)
    { src: '{{ asset('storage/' . $gallery->image) }}', title: '{{ $gallery->title }}' },
    @endforeach
];

let currentImageIndex = 0;

function openLightbox(src, title) {
    currentImageIndex = galleryImages.findIndex(img => img.src === src);
    document.getElementById('lightbox-image').src = src;
    document.getElementById('lightbox-title').textContent = title;
    document.getElementById('lightbox').classList.remove('hidden');
    document.getElementById('lightbox').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.getElementById('lightbox').classList.remove('flex');
    document.body.style.overflow = '';
}

function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
    const img = galleryImages[currentImageIndex];
    document.getElementById('lightbox-image').src = img.src;
    document.getElementById('lightbox-title').textContent = img.title;
}

function prevImage() {
    currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
    const img = galleryImages[currentImageIndex];
    document.getElementById('lightbox-image').src = img.src;
    document.getElementById('lightbox-title').textContent = img.title;
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (document.getElementById('lightbox').classList.contains('flex')) {
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowRight') nextImage();
        if (e.key === 'ArrowLeft') prevImage();
    }
});

// Close on backdrop click
document.getElementById('lightbox').addEventListener('click', function(e) {
    if (e.target === this) closeLightbox();
});
</script>
@endsection
