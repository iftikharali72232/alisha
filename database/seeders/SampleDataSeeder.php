<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Gallery;
use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create tags
        $tags = ['Laravel', 'PHP', 'JavaScript', 'CSS', 'HTML', 'React', 'Vue', 'Node.js'];

        foreach ($tags as $tagName) {
            Tag::create([
                'name' => $tagName,
                'slug' => strtolower(str_replace('.', '-', $tagName)),
            ]);
        }

        // Create sample posts
        $posts = [
            [
                'title' => 'Getting Started with Laravel 12',
                'slug' => 'getting-started-with-laravel-12',
                'content' => '<p>Laravel 12 brings exciting new features and improvements. In this post, we\'ll explore the latest updates and how to get started with the new version.</p><h2>Key Features</h2><ul><li>Improved performance</li><li>New authentication system</li><li>Enhanced routing</li></ul><p>Let\'s dive in!</p>',
                'excerpt' => 'Learn about the latest features in Laravel 12 and how to upgrade your applications.',
                'status' => 'published',
                'featured_image' => null,
                'is_featured' => true,
                'category_id' => 1,
                'user_id' => 1,
            ],
            [
                'title' => 'Building Modern Web Applications',
                'slug' => 'building-modern-web-applications',
                'content' => '<p>Modern web development requires a solid understanding of both frontend and backend technologies. This comprehensive guide covers everything you need to know.</p><h2>Frontend Technologies</h2><p>React, Vue, and Angular are the most popular frontend frameworks today.</p><h2>Backend Technologies</h2><p>Node.js, PHP, and Python are excellent choices for backend development.</p>',
                'excerpt' => 'A comprehensive guide to building modern web applications with the latest technologies.',
                'status' => 'published',
                'featured_image' => null,
                'is_featured' => false,
                'category_id' => 1,
                'user_id' => 1,
            ],
            [
                'title' => 'Travel Tips for Digital Nomads',
                'slug' => 'travel-tips-for-digital-nomads',
                'content' => '<p>Working remotely while traveling the world is a dream for many. Here are some essential tips for digital nomads.</p><h2>Visa Requirements</h2><p>Research visa requirements for your destination countries.</p><h2>Internet Connectivity</h2><p>Reliable internet is crucial for remote work.</p>',
                'excerpt' => 'Essential travel tips for digital nomads looking to work remotely while exploring the world.',
                'status' => 'published',
                'featured_image' => null,
                'is_featured' => false,
                'category_id' => 3,
                'user_id' => 1,
            ],
        ];

        foreach ($posts as $postData) {
            $post = Post::create($postData);
            $post->tags()->attach([1, 2]); // Attach some tags
        }

        // Create settings
        $settings = [
            ['key' => 'site_name', 'value' => 'VisionSphere – Explore your world of ideas and stories.', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'A beautiful blog sharing insights and stories about technology, lifestyle, and travel.', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'posts_per_page', 'value' => '10', 'type' => 'number', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'contact@visionsphere.com', 'type' => 'email', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '+1 (555) 123-4567', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => '123 Blog Street, Web City, WC 12345', 'type' => 'textarea', 'group' => 'contact'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/visionsphere', 'type' => 'url', 'group' => 'social'],
            ['key' => 'twitter_url', 'value' => 'https://twitter.com/visionsphere', 'type' => 'url', 'group' => 'social'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/visionsphere', 'type' => 'url', 'group' => 'social'],
            ['key' => 'footer_text', 'value' => '© ' . date('Y') . ' VisionSphere – Explore your world of ideas and stories. All rights reserved.', 'type' => 'text', 'group' => 'footer'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        // Create sample sliders
        $sliders = [
            [
                'title' => 'Welcome to VisionSphere',
                'description' => 'Discover amazing stories and insights',
                'image' => 'slider1.jpg',
                'link' => '/blog',
                'button_text' => 'Explore Blog',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => 'Latest Technology Trends',
                'description' => 'Stay updated with the latest in tech',
                'image' => 'slider2.jpg',
                'link' => '/category/technology',
                'button_text' => 'Read More',
                'is_active' => true,
                'order' => 2,
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }

        // Create sample gallery items
        $galleries = [
            [
                'title' => 'Beautiful Sunset',
                'image' => 'gallery1.jpg',
                'category' => 'Nature',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => 'City Lights',
                'image' => 'gallery2.jpg',
                'category' => 'Urban',
                'is_active' => true,
                'order' => 2,
            ],
        ];

        foreach ($galleries as $gallery) {
            Gallery::create($gallery);
        }

        // Create sample pages
        $pages = [
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h1>Privacy Policy</h1><p>This is our privacy policy...</p>',
                'meta_title' => 'Privacy Policy',
                'meta_description' => 'Our privacy policy and data handling practices.',
                'is_active' => true,
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => '<h1>Terms of Service</h1><p>These are our terms of service...</p>',
                'meta_title' => 'Terms of Service',
                'meta_description' => 'Terms and conditions for using our website.',
                'is_active' => true,
            ],
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }
    }
}
