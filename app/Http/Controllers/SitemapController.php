<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Page;
use App\Models\Shop;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', 'published')
            ->orderBy('updated_at', 'desc')
            ->get();

        $categories = Category::all();
        $tags = Tag::all();
        
        $pages = Page::where('is_active', true)->get();
        
        $shops = Shop::where('status', 'active')
            ->whereIn('subscription_status', ['active', 'trial'])
            ->get();

        $content = view('sitemap.index', compact('posts', 'categories', 'tags', 'pages', 'shops'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }

    public function feed()
    {
        $posts = Post::where('status', 'published')
            ->with('category', 'user')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        $siteName = \App\Models\Setting::get('site_name', 'Vision Sphere');
        $siteDescription = \App\Models\Setting::get('site_description', 'Vision Sphere - Explore your world of ideas and stories');
        $siteUrl = config('app.url', 'https://sphere.vision-erp.com');

        $content = view('sitemap.feed', compact('posts', 'siteName', 'siteDescription', 'siteUrl'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
