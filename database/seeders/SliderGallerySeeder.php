<?php

namespace Database\Seeders;

use App\Models\Slider;
use App\Models\Gallery;
use Illuminate\Database\Seeder;

class SliderGallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Sliders with placeholder images
        $sliders = [
            [
                'title' => 'Welcome to Vision Sphere',
                'description' => 'Explore your world of ideas and stories. Discover insightful articles across technology, lifestyle, business, and more.',
                'button_text' => 'Explore Now',
                'link' => '/blog',
                'image' => 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=1920&h=600&fit=crop',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Stay Informed & Inspired',
                'description' => 'Get the latest insights on technology trends, business strategies, health tips, and lifestyle advice from our expert writers.',
                'button_text' => 'Read Latest Posts',
                'link' => '/blog',
                'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=1920&h=600&fit=crop',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Share Your Voice',
                'description' => 'Join our community of passionate writers and readers. Share your stories, ideas, and expertise with the world.',
                'button_text' => 'Join Community',
                'link' => '/register',
                'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1920&h=600&fit=crop',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Discover New Perspectives',
                'description' => 'Broaden your horizons with diverse content covering travel adventures, cultural insights, and creative inspiration.',
                'button_text' => 'Start Reading',
                'link' => '/blog',
                'image' => 'https://images.unsplash.com/photo-1488190211105-8b0e65b80b4e?w=1920&h=600&fit=crop',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Knowledge is Power',
                'description' => 'Empower yourself with educational content, career advice, and personal development tips from industry experts.',
                'button_text' => 'Learn More',
                'link' => '/blog',
                'image' => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=1920&h=600&fit=crop',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Creative Expression',
                'description' => 'Unleash your creativity with art, design, photography, and digital media content from talented creators.',
                'button_text' => 'Get Inspired',
                'link' => '/blog',
                'image' => 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?w=1920&h=600&fit=crop',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'Health & Wellness',
                'description' => 'Take care of your mind, body, and spirit with expert advice on fitness, nutrition, mental health, and holistic living.',
                'button_text' => 'Live Healthy',
                'link' => '/blog',
                'image' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=1920&h=600&fit=crop',
                'order' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::updateOrCreate(
                ['title' => $slider['title']],
                $slider
            );
        }

        $this->command->info('7 Sliders created successfully!');

        // Create Gallery images with categories
        $galleryImages = [
            // Nature & Landscapes
            ['title' => 'Mountain Sunrise', 'description' => 'Beautiful sunrise over mountain peaks', 'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop', 'category' => 'Nature'],
            ['title' => 'Ocean Waves', 'description' => 'Peaceful ocean waves at sunset', 'image' => 'https://images.unsplash.com/photo-1505142468610-359e7d316be0?w=800&h=600&fit=crop', 'category' => 'Nature'],
            ['title' => 'Forest Path', 'description' => 'Serene forest walking path', 'image' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop', 'category' => 'Nature'],
            ['title' => 'Desert Dunes', 'description' => 'Golden sand dunes at golden hour', 'image' => 'https://images.unsplash.com/photo-1509316785289-025f5b846b35?w=800&h=600&fit=crop', 'category' => 'Nature'],
            ['title' => 'Waterfall Beauty', 'description' => 'Majestic waterfall in tropical forest', 'image' => 'https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?w=800&h=600&fit=crop', 'category' => 'Nature'],
            
            // Technology
            ['title' => 'Modern Workspace', 'description' => 'Clean modern tech workspace setup', 'image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800&h=600&fit=crop', 'category' => 'Technology'],
            ['title' => 'Coding Session', 'description' => 'Developer coding on multiple screens', 'image' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=800&h=600&fit=crop', 'category' => 'Technology'],
            ['title' => 'Innovation Lab', 'description' => 'Futuristic technology innovation lab', 'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&h=600&fit=crop', 'category' => 'Technology'],
            ['title' => 'AI Future', 'description' => 'Artificial intelligence concept', 'image' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=800&h=600&fit=crop', 'category' => 'Technology'],
            ['title' => 'Smart Devices', 'description' => 'Collection of smart devices', 'image' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=800&h=600&fit=crop', 'category' => 'Technology'],
            
            // Travel
            ['title' => 'Paris Streets', 'description' => 'Beautiful streets of Paris', 'image' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?w=800&h=600&fit=crop', 'category' => 'Travel'],
            ['title' => 'Tokyo Nights', 'description' => 'Vibrant Tokyo nightlife', 'image' => 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=800&h=600&fit=crop', 'category' => 'Travel'],
            ['title' => 'Santorini Views', 'description' => 'Stunning Santorini white buildings', 'image' => 'https://images.unsplash.com/photo-1570077188670-e3a8d69ac5ff?w=800&h=600&fit=crop', 'category' => 'Travel'],
            ['title' => 'Bali Temple', 'description' => 'Ancient temple in Bali', 'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=800&h=600&fit=crop', 'category' => 'Travel'],
            ['title' => 'New York Skyline', 'description' => 'Iconic New York City skyline', 'image' => 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?w=800&h=600&fit=crop', 'category' => 'Travel'],
            
            // Food
            ['title' => 'Gourmet Dish', 'description' => 'Exquisite gourmet plating', 'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&h=600&fit=crop', 'category' => 'Food'],
            ['title' => 'Fresh Produce', 'description' => 'Colorful fresh vegetables', 'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800&h=600&fit=crop', 'category' => 'Food'],
            ['title' => 'Coffee Art', 'description' => 'Beautiful latte art', 'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=800&h=600&fit=crop', 'category' => 'Food'],
            ['title' => 'Bakery Fresh', 'description' => 'Fresh baked pastries', 'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&h=600&fit=crop', 'category' => 'Food'],
            ['title' => 'Sushi Platter', 'description' => 'Premium sushi selection', 'image' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=800&h=600&fit=crop', 'category' => 'Food'],
            
            // Lifestyle
            ['title' => 'Morning Yoga', 'description' => 'Peaceful morning yoga session', 'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800&h=600&fit=crop', 'category' => 'Lifestyle'],
            ['title' => 'Reading Corner', 'description' => 'Cozy reading nook', 'image' => 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=800&h=600&fit=crop', 'category' => 'Lifestyle'],
            ['title' => 'Home Office', 'description' => 'Minimalist home office', 'image' => 'https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?w=800&h=600&fit=crop', 'category' => 'Lifestyle'],
            ['title' => 'Fitness Goals', 'description' => 'Workout and fitness motivation', 'image' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=800&h=600&fit=crop', 'category' => 'Lifestyle'],
            ['title' => 'Plant Care', 'description' => 'Indoor plants and greenery', 'image' => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&h=600&fit=crop', 'category' => 'Lifestyle'],
            
            // Business
            ['title' => 'Team Meeting', 'description' => 'Professional team collaboration', 'image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&h=600&fit=crop', 'category' => 'Business'],
            ['title' => 'Startup Office', 'description' => 'Modern startup workspace', 'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&h=600&fit=crop', 'category' => 'Business'],
            ['title' => 'Success Path', 'description' => 'Business growth and success', 'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=600&fit=crop', 'category' => 'Business'],
            ['title' => 'Networking Event', 'description' => 'Professional networking', 'image' => 'https://images.unsplash.com/photo-1515187029135-18ee286d815b?w=800&h=600&fit=crop', 'category' => 'Business'],
            ['title' => 'Analytics Dashboard', 'description' => 'Data analytics and reporting', 'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop', 'category' => 'Business'],
            
            // Sports & Fitness
            ['title' => 'Gym Workout', 'description' => 'Intense gym training session', 'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop', 'category' => 'Sports'],
            ['title' => 'Running Track', 'description' => 'Professional running track', 'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop', 'category' => 'Sports'],
            ['title' => 'Basketball Court', 'description' => 'Urban basketball game', 'image' => 'https://images.unsplash.com/photo-1574623452334-1e0ac2b3ccb4?w=800&h=600&fit=crop', 'category' => 'Sports'],
            ['title' => 'Yoga Session', 'description' => 'Peaceful outdoor yoga', 'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800&h=600&fit=crop', 'category' => 'Sports'],
            ['title' => 'Swimming Pool', 'description' => 'Olympic swimming competition', 'image' => 'https://images.unsplash.com/photo-1530549387789-4c1017266635?w=800&h=600&fit=crop', 'category' => 'Sports'],
        ];

        foreach ($galleryImages as $image) {
            Gallery::updateOrCreate(
                ['title' => $image['title']],
                [
                    'title' => $image['title'],
                    'description' => $image['description'],
                    'image' => $image['image'],
                    'category' => $image['category'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('35 Gallery images created successfully!');
    }
}
