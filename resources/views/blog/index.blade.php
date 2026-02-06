@extends('layouts.blog')

@section('title', 'Home - Vision Sphere Blog')
@section('meta_description', 'Vision Sphere - Explore insightful articles across technology, lifestyle, business, health, wellness, beauty, travel and more. Your premier destination for quality content.')
@section('canonical_url', route('home'))

@section('content')
    <!-- Hero Slider -->
    @if($sliders->count() > 0)
    <section class="relative">
        <div id="slider" class="relative overflow-hidden bg-gray-900" style="height: 500px;">
            @foreach($sliders as $index => $slider)
            <div class="slider-slide absolute inset-0 transition-opacity duration-500 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}" data-index="{{ $index }}">
                @if(Str::startsWith($slider->image, 'http'))
                    <img src="{{ $slider->image }}" alt="{{ $slider->title }}" class="w-full h-full object-cover">
                @else
                    <img src="{{ Storage::url($slider->image) }}" alt="{{ $slider->title }}" class="w-full h-full object-cover">
                @endif
                <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent"></div>
                <div class="absolute inset-0 flex items-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="max-w-xl">
                            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ $slider->title }}</h1>
                            @if($slider->description)
                            <p class="text-lg text-gray-200 mb-6">{{ $slider->description }}</p>
                            @endif
                            @if($slider->button_text && $slider->link)
                            <a href="{{ $slider->link }}" class="inline-flex items-center px-6 py-3 bg-rose-600 text-white rounded-full hover:bg-rose-700 transition">
                                {{ $slider->button_text }}
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            
            <!-- Slider Controls -->
            @if($sliders->count() > 1)
            <button onclick="prevSlide()" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white/20 backdrop-blur-sm text-white rounded-full hover:bg-white/30 transition">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button onclick="nextSlide()" class="absolute right-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white/20 backdrop-blur-sm text-white rounded-full hover:bg-white/30 transition">
                <i class="fas fa-chevron-right"></i>
            </button>
            
            <!-- Slider Dots -->
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-2">
                @foreach($sliders as $index => $slider)
                <button onclick="goToSlide({{ $index }})" class="slider-dot w-3 h-3 rounded-full transition {{ $index === 0 ? 'bg-white' : 'bg-white/50' }}" data-index="{{ $index }}"></button>
                @endforeach
            </div>
            @endif
        </div>
    </section>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Featured Posts -->
        @if($featuredPosts->count() > 0)
        <section class="mb-16">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Featured Posts</h2>
                <a href="{{ route('blog.index') }}" class="text-rose-600 hover:text-rose-700 font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredPosts as $post)
                <article class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition group">
                    <a href="{{ route('blog.show', $post->slug) }}" class="block">
                        <div class="relative overflow-hidden" style="padding-bottom: 60%;">
                            @if($post->featured_image)
                            @php
                                $featuredImageUrl = Str::startsWith($post->featured_image, 'http') ? $post->featured_image : Storage::url($post->featured_image);
                            @endphp
                            <img src="{{ $featuredImageUrl }}" alt="{{ $post->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                            <div class="absolute inset-0 bg-gradient-to-br from-rose-400 to-purple-500"></div>
                            @endif
                            <span class="absolute top-4 left-4 bg-rose-600 text-white text-xs px-3 py-1 rounded-full">
                                Featured
                            </span>
                        </div>
                    </a>
                    <div class="p-6">
                        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-3">
                            @if($post->category)
                            <a href="{{ route('blog.category', $post->category->slug) }}" class="text-rose-600 hover:text-rose-700">{{ $post->category->name }}</a>
                            <span>•</span>
                            @endif
                            <span>{{ $post->created_at->format('M d, Y') }}</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 line-clamp-2">
                            <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-rose-600 transition">{{ $post->title }}</a>
                        </h3>
                        <p class="text-gray-600 line-clamp-3 mb-4">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                        <div class="flex items-center">
                            <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="w-8 h-8 rounded-full">
                            <span class="ml-2 text-sm text-gray-700">{{ $post->user->name }}</span>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Featured Shops Section -->
        @if(isset($featuredShops) && $featuredShops->count() > 0)
        <section class="mb-16">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Featured Shops</h2>
                    <p class="text-gray-600 mt-1">Discover amazing shops from our community</p>
                </div>
                <a href="{{ route('shops.index') }}" class="text-rose-600 hover:text-rose-700 font-medium">
                    View All Shops <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredShops as $shop)
                <a href="{{ route('shop.show', $shop->slug) }}" class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition group block">
                    <!-- Shop Banner/Image -->
                    <div class="relative h-40 bg-gradient-to-r from-pink-400 to-rose-500">
                        @if($shop->banner)
                            <img src="{{ Storage::url($shop->banner) }}" alt="{{ $shop->name }}" class="w-full h-full object-cover">
                        @endif
                        
                        <!-- Subscription Badge -->
                        <div class="absolute top-4 right-4">
                            @if($shop->subscription_status === 'trial')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-500 text-white shadow-lg">
                                    <i class="fas fa-hourglass-half mr-1"></i> Trial
                                </span>
                            @elseif($shop->activeSubscription?->plan?->slug === 'pro')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-yellow-400 to-orange-500 text-white shadow-lg">
                                    <i class="fas fa-crown mr-1"></i> Pro
                                </span>
                            @elseif($shop->activeSubscription?->plan)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500 text-white shadow-lg">
                                    <i class="fas fa-check-circle mr-1"></i> {{ $shop->activeSubscription->plan->name }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Shop Logo -->
                        <div class="absolute -bottom-8 left-6">
                            @if($shop->logo)
                                <img src="{{ Storage::url($shop->logo) }}" alt="{{ $shop->name }}" class="w-16 h-16 rounded-xl object-cover border-4 border-white shadow-md bg-white">
                            @else
                                <div class="w-16 h-16 rounded-xl bg-white border-4 border-white shadow-md flex items-center justify-center">
                                    <span class="text-2xl font-bold text-rose-500">{{ substr($shop->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Shop Info -->
                    <div class="pt-10 px-6 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-rose-600 transition">{{ $shop->name }}</h3>
                        <p class="text-gray-500 text-sm mt-1 line-clamp-2">{{ Str::limit($shop->description, 80) }}</p>
                        
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-map-marker-alt mr-1 text-rose-400"></i>
                                {{ $shop->city ?? 'Pakistan' }}
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-box mr-1 text-rose-400"></i>
                                {{ $shop->products_count ?? $shop->products()->count() }} Products
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Latest Posts</h2>
                    
                    @if($posts->count() > 0)
                    <div class="space-y-8">
                        @foreach($posts as $post)
                        <article class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition">
                            <div class="md:flex">
                                <a href="{{ route('blog.show', $post->slug) }}" class="md:w-1/3 flex-shrink-0">
                                    <div class="relative overflow-hidden" style="padding-bottom: 75%;">
                                        @if($post->featured_image)
                                        @php
                                            $featuredImageUrl = Str::startsWith($post->featured_image, 'http') ? $post->featured_image : Storage::url($post->featured_image);
                                        @endphp
                                        <img src="{{ $featuredImageUrl }}" alt="{{ $post->title }}" class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition duration-300">
                                        @else
                                        <div class="absolute inset-0 bg-gradient-to-br from-rose-400 to-purple-500"></div>
                                        @endif
                                    </div>
                                </a>
                                <div class="p-6 md:w-2/3">
                                    <div class="flex items-center space-x-2 text-sm text-gray-500 mb-3">
                                        @if($post->category)
                                        <a href="{{ route('blog.category', $post->category->slug) }}" class="text-rose-600 hover:text-rose-700">{{ $post->category->name }}</a>
                                        <span>•</span>
                                        @endif
                                        <span>{{ $post->created_at->format('M d, Y') }}</span>
                                        <span>•</span>
                                        <span>{{ $post->comments_count ?? 0 }} comments</span>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-3">
                                        <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-rose-600 transition">{{ $post->title }}</a>
                                    </h3>
                                    <p class="text-gray-600 line-clamp-2 mb-4">{{ Str::limit(strip_tags($post->content), 150) }}</p>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="w-8 h-8 rounded-full">
                                            <span class="ml-2 text-sm text-gray-700">{{ $post->user->name }}</span>
                                        </div>
                                        <a href="{{ route('blog.show', $post->slug) }}" class="text-rose-600 hover:text-rose-700 text-sm font-medium">
                                            Read More <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $posts->links() }}
                    </div>
                    @else
                    <div class="bg-white rounded-2xl p-12 text-center">
                        <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">No posts available yet.</p>
                    </div>
                    @endif
                </section>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                @include('blog.partials.sidebar', ['categories' => $categories, 'popularPosts' => $popularPosts])
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
let currentSlide = 0;
const totalSlides = {{ $sliders->count() }};

function showSlide(index) {
    const slides = document.querySelectorAll('.slider-slide');
    const dots = document.querySelectorAll('.slider-dot');
    
    slides.forEach((slide, i) => {
        slide.classList.toggle('opacity-100', i === index);
        slide.classList.toggle('opacity-0', i !== index);
    });
    
    dots.forEach((dot, i) => {
        dot.classList.toggle('bg-white', i === index);
        dot.classList.toggle('bg-white/50', i !== index);
    });
    
    currentSlide = index;
}

function nextSlide() {
    showSlide((currentSlide + 1) % totalSlides);
}

function prevSlide() {
    showSlide((currentSlide - 1 + totalSlides) % totalSlides);
}

function goToSlide(index) {
    showSlide(index);
}

// Auto-advance slider every 5 seconds
@if($sliders->count() > 1)
setInterval(nextSlide, 5000);
@endif
</script>
@endsection
