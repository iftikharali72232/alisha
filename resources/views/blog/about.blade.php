@extends('layouts.blog')

@section('title', 'About Us')

@section('content')
    <!-- About Header -->
    <div class="bg-gradient-to-r from-rose-500 to-purple-600 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">About Us</h1>
            <p class="text-white/80 text-lg max-w-2xl mx-auto">Learn more about our story, mission, and the people behind {{ \App\Models\Setting::get('site_name', 'VisionSphere – Explore your world of ideas and stories.') }}.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Our Story -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Story</h2>
                <div class="prose prose-lg text-gray-600">
                    <p>{{ \App\Models\Setting::get('site_description', 'Welcome to our blog! We share insights, stories, and experiences with our community.') }}</p>
                    <p>We started this journey with a simple goal: to create a space where ideas can flourish and stories can be shared. Every article we publish is crafted with care and dedication.</p>
                    <p>Our platform is more than just a blog—it's a community of like-minded individuals who share a passion for knowledge, creativity, and meaningful connections.</p>
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
                <p class="text-gray-600">To inspire, educate, and connect people through thoughtful content that sparks curiosity and encourages personal growth. We believe in the power of words to make a difference.</p>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-3xl p-8">
                <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-eye text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h3>
                <p class="text-gray-600">To become a leading voice in our niche, known for authentic storytelling, valuable insights, and a supportive community that values quality over quantity.</p>
            </div>
        </div>

        <!-- Team Section -->
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Meet Our Team</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">The passionate people behind our content.</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach(\App\Models\User::where('is_admin', true)->get() as $member)
            <div class="bg-white rounded-3xl shadow-sm p-8 text-center group hover:shadow-lg transition">
                <img src="{{ $member->avatar ? asset('storage/' . $member->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=f43f5e&color=fff&size=150' }}" alt="{{ $member->name }}" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover group-hover:scale-105 transition">
                <h3 class="text-xl font-semibold text-gray-900">{{ $member->name }}</h3>
                <p class="text-rose-600 text-sm mb-3">{{ $member->role ?? 'Author' }}</p>
                <p class="text-gray-600 text-sm mb-4">{{ $member->bio ?? 'Passionate about sharing knowledge and connecting with readers.' }}</p>
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
            <h2 class="text-3xl font-bold text-white mb-4">Want to Connect?</h2>
            <p class="text-white/80 mb-8 max-w-2xl mx-auto">We'd love to hear from you. Whether you have a question, feedback, or just want to say hello, don't hesitate to reach out!</p>
            <a href="{{ route('blog.contact') }}" class="inline-flex items-center px-8 py-3 bg-white text-rose-600 rounded-full font-medium hover:bg-rose-50 transition">
                Get in Touch
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
@endsection
