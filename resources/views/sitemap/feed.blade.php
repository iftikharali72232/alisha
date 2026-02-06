{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel>
        <title>{{ htmlspecialchars($siteName, ENT_XML1, 'UTF-8') }}</title>
        <link>{{ $siteUrl }}</link>
        <description>{{ htmlspecialchars($siteDescription, ENT_XML1, 'UTF-8') }}</description>
        <language>en-us</language>
        <lastBuildDate>{{ $posts->first()?->created_at?->toRssString() ?? now()->toRssString() }}</lastBuildDate>
        <atom:link href="{{ url('/feed') }}" rel="self" type="application/rss+xml"/>
        <generator>Vision Sphere</generator>

        @foreach($posts as $post)
        <item>
            <title>{{ htmlspecialchars($post->title, ENT_XML1, 'UTF-8') }}</title>
            <link>{{ route('blog.show', $post->slug) }}</link>
            <guid isPermaLink="true">{{ route('blog.show', $post->slug) }}</guid>
            <description>{{ htmlspecialchars(Str::limit(strip_tags($post->excerpt ?? $post->content), 300), ENT_XML1, 'UTF-8') }}</description>
            <dc:creator>{{ htmlspecialchars($post->user->name, ENT_XML1, 'UTF-8') }}</dc:creator>
            @if($post->category)
            <category>{{ htmlspecialchars($post->category->name, ENT_XML1, 'UTF-8') }}</category>
            @endif
            <pubDate>{{ $post->created_at->toRssString() }}</pubDate>
        </item>
        @endforeach
    </channel>
</rss>
