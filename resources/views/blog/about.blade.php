@extends('layouts.blog')

@section('title', 'About Us')
@section('meta_description', 'Learn about Vision Sphere - your premier destination for insightful articles, creative stories, thought-provoking content, and a thriving e-commerce marketplace.')
@section('canonical_url', route('blog.about'))

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-white border-b" aria-label="Breadcrumb">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-rose-600 transition"><i class="fas fa-home"></i></a></li>
                <li><span class="mx-1">/</span></li>
                <li class="text-gray-900 font-medium">About Us</li>
            </ol>
        </div>
    </nav>

    <!-- About Header -->
    <div class="bg-gradient-to-r from-rose-500 to-purple-600 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">About Vision Sphere</h1>
            <p class="text-white/80 text-lg max-w-2xl mx-auto">Your premier destination for insightful articles, creative stories, thought-provoking content, and a thriving e-commerce marketplace.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Our Story -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Story</h2>
                <div class="prose prose-lg text-gray-600">
                    <p>{{ \App\Models\Setting::get('site_description', 'Vision Sphere is your premier destination for insightful articles, creative stories, and thought-provoking content.') }}</p>
                    <p>Founded with the vision to create a platform where knowledge meets creativity, Vision Sphere serves as a space for writers, readers, and entrepreneurs to share their experiences, insights, and expertise across technology, lifestyle, business, health, and more.</p>
                    <p>Our community celebrates the diversity of ideas and perspectives while fostering meaningful connections. We believe that by sharing knowledge and stories, we empower each other to grow personally and professionally, and inspire positive change in our communities.</p>
                    <p>Beyond our blog, Vision Sphere features a growing e-commerce marketplace where local businesses can showcase their products and connect with customers across Pakistan and beyond.</p>
                </div>
            </div>
            <div class="relative">
                <div class="bg-gradient-to-br from-rose-100 to-purple-100 rounded-3xl p-8">
                    <img src="{{ asset('images/about-image.jpg') }}" alt="About Us" class="rounded-2xl shadow-lg w-full" onerror="this.style.display='none'">
                    <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-rose-500 rounded-2xl -z-10"></div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="bg-white rounded-3xl shadow-sm p-8 md:p-12 mb-20">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold text-rose-600 mb-2">{{ \App\Models\Post::where('status', 'published')->count() }}+</div>
                    <div class="text-gray-600">Articles Published</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-rose-600 mb-2">{{ \App\Models\Category::count() }}+</div>
                    <div class="text-gray-600">Categories</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-rose-600 mb-2">{{ \App\Models\User::count() }}+</div>
                    <div class="text-gray-600">Team Members</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-rose-600 mb-2">{{ \App\Models\Comment::where('status', 'approved')->count() }}+</div>
                    <div class="text-gray-600">Happy Readers</div>
                </div>
            </div>
        </div>

        <!-- Mission & Vision -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-20">
            <div class="bg-gradient-to-br from-rose-50 to-pink-50 rounded-3xl p-8">
                <div class="w-16 h-16 bg-rose-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-bullseye text-2xl text-rose-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h3>
                <p class="text-gray-600">To create a vibrant platform where writers, readers, and entrepreneurs can share authentic stories, gain valuable insights, and connect with others on their journey of personal and professional growth.</p>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-3xl p-8">
                <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-eye text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h3>
                <p class="text-gray-600">To become a leading platform that empowers people worldwide by celebrating diverse ideas, fostering meaningful connections, and inspiring positive change through quality content and innovative e-commerce solutions.</p>
            </div>
        </div>

        <!-- Team Section -->
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Meet Our Team</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">The passionate team behind Vision Sphere, dedicated to creating quality content and building meaningful connections.</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach(\App\Models\User::where('is_admin', true)->with('role')->get() as $member)
            <div class="bg-white rounded-3xl shadow-sm p-8 text-center group hover:shadow-lg transition">
                <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover group-hover:scale-105 transition">
                <h3 class="text-xl font-semibold text-gray-900">{{ $member->name }}</h3>
                <p class="text-rose-600 text-sm mb-3">{{ $member->role?->name ?? 'Author' }}</p>
                <p class="text-gray-600 text-sm mb-4">{{ $member->bio ?? 'Dedicated to creating quality content and empowering readers through insightful articles.' }}</p>
                <div class="flex justify-center space-x-3">
                    @if($member->facebook_url)
                    <a href="{{ $member->facebook_url }}" target="_blank" class="text-gray-400 hover:text-blue-600 transition">
                        <i class="fab fa-facebook"></i>
                    </a>
                    @endif
                    @if($member->twitter_url)
                    <a href="{{ $member->twitter_url }}" target="_blank" class="text-gray-400 hover:text-sky-500 transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    @endif
                    @if($member->instagram_url)
                    <a href="{{ $member->instagram_url }}" target="_blank" class="text-gray-400 hover:text-pink-500 transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- CTA -->
        <div class="mt-20 bg-gradient-to-r from-rose-500 to-purple-600 rounded-3xl p-12 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Join Our Community</h2>
            <p class="text-white/80 mb-8 max-w-2xl mx-auto">Ready to share your story or start your online shop? Join Vision Sphere today and be part of a growing community of writers, readers, and entrepreneurs.</p>
            <a href="{{ route('blog.contact') }}" class="inline-flex items-center px-8 py-3 bg-white text-rose-600 rounded-full font-medium hover:bg-rose-50 transition">
                Get in Touch
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
@endsection
