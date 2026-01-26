<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class AddPostImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Image categories with Unsplash URLs
        $imagesByCategory = [
            'Technology' => [
                'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1488590528505-98d2b5aba04b?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=800&h=500&fit=crop',
            ],
            'Business' => [
                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1444653614773-995cb1ef9efa?w=800&h=500&fit=crop',
            ],
            'Health' => [
                'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1505576399279-565b52d4ac71?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1535914254981-b5012eebbd15?w=800&h=500&fit=crop',
            ],
            'Travel' => [
                'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1530789253388-582c481c54b0?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=800&h=500&fit=crop',
            ],
            'Lifestyle' => [
                'https://images.unsplash.com/photo-1489371004194-a16e05c6e6e4?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1484627147104-f5197bcd6651?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&h=500&fit=crop',
            ],
            'Finance' => [
                'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1579621970588-a35d0e7ab9b6?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1553729459-efe14ef6055d?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1565372195458-9de0b320ef04?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1518458028785-8fbcd101ebb9?w=800&h=500&fit=crop',
            ],
            'Food' => [
                'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=800&h=500&fit=crop',
            ],
            'Sports' => [
                'https://images.unsplash.com/photo-1461896836934- voices?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1517649763962-0c623066013b?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1541252260730-0412e8e2108e?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1571731956672-f2b94d7dd0cb?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=800&h=500&fit=crop',
            ],
            'Education' => [
                'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1491841550275-ad7854e35ca6?w=800&h=500&fit=crop',
            ],
            'Entertainment' => [
                'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1514533450685-4493e01d1fdc?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1598899134739-24c46f58b8c0?w=800&h=500&fit=crop',
                'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=800&h=500&fit=crop',
            ],
        ];

        // Default images for other categories
        $defaultImages = [
            'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=800&h=500&fit=crop',
            'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=500&fit=crop',
            'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&h=500&fit=crop',
            'https://images.unsplash.com/photo-1488190211105-8b0e65b80b4e?w=800&h=500&fit=crop',
            'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=800&h=500&fit=crop',
        ];

        $posts = Post::with('category')->get();
        $updatedCount = 0;

        foreach ($posts as $post) {
            $categoryName = $post->category?->name ?? 'default';
            $images = $imagesByCategory[$categoryName] ?? $defaultImages;
            
            // Select 2-3 random images for gallery
            $galleryCount = rand(2, 3);
            $galleryImages = [];
            $shuffled = $images;
            shuffle($shuffled);
            
            for ($i = 0; $i < min($galleryCount, count($shuffled)); $i++) {
                $galleryImages[] = $shuffled[$i];
            }

            // Set featured image if not set
            if (empty($post->featured_image)) {
                $post->featured_image = $images[array_rand($images)];
            }

            // Add gallery images
            $existingGallery = $post->gallery_images ?? [];
            if (is_string($existingGallery)) {
                $existingGallery = json_decode($existingGallery, true) ?? [];
            }
            
            if (count($existingGallery) < 2) {
                $post->gallery_images = array_merge($existingGallery, $galleryImages);
            }

            $post->save();
            $updatedCount++;
        }

        $this->command->info("Updated {$updatedCount} posts with images!");
    }
}
