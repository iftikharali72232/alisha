{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

    <!-- Homepage -->
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- Blog Index -->
    <url>
        <loc>{{ route('blog.index') }}</loc>
        <lastmod>{{ $posts->first()?->updated_at?->toAtomString() ?? now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    <!-- About Page -->
    <url>
        <loc>{{ route('blog.about') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>

    <!-- Contact Page -->
    <url>
        <loc>{{ route('blog.contact') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>

    <!-- Gallery Page -->
    <url>
        <loc>{{ route('blog.gallery') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>

    <!-- Static Pages (Privacy, Terms, Disclaimer) -->
    @foreach($pages as $page)
    <url>
        <loc>{{ route('blog.page', $page->slug) }}</loc>
        <lastmod>{{ $page->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    @endforeach

    <!-- Blog Posts -->
    @foreach($posts as $post)
    <url>
        <loc>{{ route('blog.show', $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
        @if($post->featured_image)
        <image:image>
            <image:loc>{{ Str::startsWith($post->featured_image, 'http') ? $post->featured_image : asset('storage/' . $post->featured_image) }}</image:loc>
            <image:title>{{ htmlspecialchars($post->title, ENT_XML1, 'UTF-8') }}</image:title>
        </image:image>
        @endif
    </url>
    @endforeach

    <!-- Categories -->
    @foreach($categories as $category)
    <url>
        <loc>{{ route('blog.category', $category->slug) }}</loc>
        <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    <!-- Tags -->
    @foreach($tags as $tag)
    <url>
        <loc>{{ route('blog.tag', $tag->slug) }}</loc>
        <lastmod>{{ $tag->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    @endforeach

    <!-- Shops -->
    @foreach($shops as $shop)
    <url>
        <loc>{{ route('shop.show', $shop->slug) }}</loc>
        <lastmod>{{ $shop->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

</urlset>
