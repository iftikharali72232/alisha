<!-- Sidebar -->
<aside class="space-y-8">
    <!-- Search -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Search</h3>
        <form action="{{ route('blog.search') }}" method="GET">
            <div class="relative">
                <input type="text" name="q" placeholder="Search posts..." class="w-full pl-4 pr-10 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-rose-600">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Categories -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Categories</h3>
        <ul class="space-y-3">
            @foreach($categories as $category)
            <li>
                <a href="{{ route('blog.category', $category->slug) }}" class="flex items-center justify-between text-gray-600 hover:text-rose-600 transition">
                    <div class="flex items-center space-x-3">
                        @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-8 h-8 rounded-lg object-cover">
                        @else
                        <div class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center">
                            <i class="fas fa-folder text-rose-600 text-sm"></i>
                        </div>
                        @endif
                        <span>{{ $category->name }}</span>
                    </div>
                    <span class="text-sm bg-gray-100 px-2 py-1 rounded-full">{{ $category->posts_count ?? 0 }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    
    <!-- Popular Posts -->
    @if(isset($popularPosts) && $popularPosts->count() > 0)
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Popular Posts</h3>
        <div class="space-y-4">
            @foreach($popularPosts as $post)
            <article class="flex space-x-4">
                <a href="{{ route('blog.show', $post->slug) }}" class="flex-shrink-0">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-16 h-16 rounded-lg object-cover">
                    @else
                    <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-rose-400 to-purple-500"></div>
                    @endif
                </a>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-medium text-gray-900 line-clamp-2">
                        <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-rose-600 transition">{{ $post->title }}</a>
                    </h4>
                    <p class="text-xs text-gray-500 mt-1">{{ $post->created_at->format('M d, Y') }}</p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- Newsletter -->
    <div class="bg-gradient-to-br from-rose-500 to-purple-600 rounded-2xl p-6 text-white">
        <h3 class="text-lg font-semibold mb-2">Subscribe to Newsletter</h3>
        <p class="text-rose-100 text-sm mb-4">Get the latest posts delivered right to your inbox.</p>
        <form action="#" method="POST">
            @csrf
            <input type="email" name="email" placeholder="Your email address" class="w-full px-4 py-3 rounded-lg bg-white/20 backdrop-blur-sm text-white placeholder-white/70 border border-white/30 focus:outline-none focus:ring-2 focus:ring-white mb-3">
            <button type="submit" class="w-full py-3 bg-white text-rose-600 rounded-lg font-medium hover:bg-rose-50 transition">
                Subscribe
            </button>
        </form>
    </div>
    
    <!-- Social Links -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Follow Us</h3>
        <div class="flex space-x-3">
            @if(\App\Models\Setting::get('facebook_url'))
            <a href="{{ \App\Models\Setting::get('facebook_url') }}" target="_blank" class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:bg-blue-700 transition">
                <i class="fab fa-facebook-f"></i>
            </a>
            @endif
            @if(\App\Models\Setting::get('twitter_url'))
            <a href="{{ \App\Models\Setting::get('twitter_url') }}" target="_blank" class="w-10 h-10 bg-sky-500 text-white rounded-lg flex items-center justify-center hover:bg-sky-600 transition">
                <i class="fab fa-twitter"></i>
            </a>
            @endif
            @if(\App\Models\Setting::get('instagram_url'))
            <a href="{{ \App\Models\Setting::get('instagram_url') }}" target="_blank" class="w-10 h-10 bg-gradient-to-br from-purple-600 to-pink-500 text-white rounded-lg flex items-center justify-center hover:from-purple-700 hover:to-pink-600 transition">
                <i class="fab fa-instagram"></i>
            </a>
            @endif
            @if(\App\Models\Setting::get('youtube_url'))
            <a href="{{ \App\Models\Setting::get('youtube_url') }}" target="_blank" class="w-10 h-10 bg-red-600 text-white rounded-lg flex items-center justify-center hover:bg-red-700 transition">
                <i class="fab fa-youtube"></i>
            </a>
            @endif
        </div>
    </div>
</aside>
