<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Technology', 'slug' => 'technology', 'description' => 'Posts about technology, programming and software.'],
            ['name' => 'Business', 'slug' => 'business', 'description' => 'Business, startups, entrepreneurship and leadership.'],
            ['name' => 'Finance', 'slug' => 'finance', 'description' => 'Personal finance, investing, budgeting and money management.'],
            ['name' => 'Marketing', 'slug' => 'marketing', 'description' => 'Digital marketing, SEO, social media and growth.'],
            ['name' => 'Health', 'slug' => 'health', 'description' => 'Physical and mental health, fitness and wellness.'],
            ['name' => 'Food', 'slug' => 'food', 'description' => 'Recipes, restaurants, cooking tips and food culture.'],
            ['name' => 'Lifestyle', 'slug' => 'lifestyle', 'description' => 'Lifestyle, habits, personal development and daily life.'],
            ['name' => 'Travel', 'slug' => 'travel', 'description' => 'Travel guides, tips, destinations and experiences.'],
            ['name' => 'Photography', 'slug' => 'photography', 'description' => 'Photography tips, gear and photo stories.'],
            ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports news, analysis and fitness.'],
            ['name' => 'Entertainment', 'slug' => 'entertainment', 'description' => 'Movies, TV, pop culture and celebrity news.'],
            ['name' => 'Science', 'slug' => 'science', 'description' => 'Scientific discoveries, research and commentary.'],
            ['name' => 'Education', 'slug' => 'education', 'description' => 'Learning, teaching methods and educational resources.'],
            ['name' => 'Politics', 'slug' => 'politics', 'description' => 'Political commentary, analysis and news.'],
            ['name' => 'DIY', 'slug' => 'diy', 'description' => 'Do-it-yourself projects, home improvement and crafts.'],
            ['name' => 'Parenting', 'slug' => 'parenting', 'description' => 'Parenting advice, childcare and family life.'],
            ['name' => 'Culture', 'slug' => 'culture', 'description' => 'Arts, society and cultural commentary.'],
            ['name' => 'Automotive', 'slug' => 'automotive', 'description' => 'Cars, bikes and automotive industry news.'],
            ['name' => 'Gaming', 'slug' => 'gaming', 'description' => 'Video games, reviews and gaming culture.'],
            ['name' => 'Art & Design', 'slug' => 'art-design', 'description' => 'Art, design, UI/UX and creative process.'],
            ['name' => 'Music', 'slug' => 'music', 'description' => 'Music news, reviews and artist features.'],
            ['name' => 'Career', 'slug' => 'career', 'description' => 'Career advice, job search and professional growth.'],
            ['name' => 'Productivity', 'slug' => 'productivity', 'description' => 'Time management, tools and workflows.'],
            ['name' => 'Wellness', 'slug' => 'wellness', 'description' => 'Mindfulness, self-care and holistic health.'],
            ['name' => 'Beauty', 'slug' => 'beauty', 'description' => 'Beauty tips, skincare and makeup.'],
            ['name' => 'Home', 'slug' => 'home', 'description' => 'Home decor, gardening and lifestyle at home.'],
            ['name' => 'Relationships', 'slug' => 'relationships', 'description' => 'Advice on relationships, dating and communication.'],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::updateOrCreate([
                'slug' => $cat['slug']
            ], $cat);
        }
    }
}
