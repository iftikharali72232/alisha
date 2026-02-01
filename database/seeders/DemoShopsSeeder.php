<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\ShopProduct;
use App\Models\ShopSubscription;
use App\Models\ShopSubscriptionPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DemoShopsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create subscription plans if they don't exist
        $trialPlan = ShopSubscriptionPlan::firstOrCreate(
            ['slug' => 'trial'],
            [
                'name' => 'Trial',
                'description' => 'Free trial with limited features',
                'price' => 0,
                'billing_cycle' => 'monthly',
                'trial_days' => 30,
                'max_products' => 10,
                'max_categories' => 5,
                'max_coupons' => 3,
                'max_sliders' => 3,
                'max_gallery_images' => 10,
                'loyalty_enabled' => false,
                'advanced_analytics' => false,
                'custom_domain' => false,
                'is_active' => true,
                'order' => 0,
            ]
        );

        $basicPlan = ShopSubscriptionPlan::firstOrCreate(
            ['slug' => 'basic'],
            [
                'name' => 'Basic',
                'description' => 'Perfect for small businesses',
                'price' => 999,
                'billing_cycle' => 'monthly',
                'trial_days' => 0,
                'max_products' => 50,
                'max_categories' => 20,
                'max_coupons' => 10,
                'max_sliders' => 5,
                'max_gallery_images' => 50,
                'loyalty_enabled' => true,
                'advanced_analytics' => false,
                'custom_domain' => false,
                'is_active' => true,
                'is_featured' => false,
                'order' => 1,
            ]
        );

        $proPlan = ShopSubscriptionPlan::firstOrCreate(
            ['slug' => 'pro'],
            [
                'name' => 'Professional',
                'description' => 'Best for growing businesses',
                'price' => 2499,
                'billing_cycle' => 'monthly',
                'trial_days' => 0,
                'max_products' => -1, // Unlimited
                'max_categories' => -1,
                'max_coupons' => -1,
                'max_sliders' => -1,
                'max_gallery_images' => -1,
                'loyalty_enabled' => true,
                'advanced_analytics' => true,
                'custom_domain' => true,
                'is_active' => true,
                'is_featured' => true,
                'order' => 2,
            ]
        );

        // Demo shop data
        $demoShops = [
            [
                'user' => [
                    'name' => 'Sarah Khan',
                    'email' => 'sarah@demo.com',
                    'password' => 'password123',
                ],
                'shop' => [
                    'name' => 'Fashion Hub',
                    'description' => 'Premium fashion and clothing store with the latest trends in women\'s wear.',
                    'email' => 'contact@fashionhub.com',
                    'phone' => '+92 300 1234567',
                    'whatsapp' => '+92 300 1234567',
                    'address' => 'Shop 45, Dolmen Mall, Clifton',
                    'city' => 'Karachi',
                    'country' => 'Pakistan',
                    'currency' => 'PKR',
                ],
                'subscription_type' => 'subscribed', // Pro plan
                'plan' => $proPlan,
                'categories' => ['Women\'s Wear', 'Accessories', 'Footwear', 'Bags'],
                'products_count' => 15,
            ],
            [
                'user' => [
                    'name' => 'Ahmed Ali',
                    'email' => 'ahmed@demo.com',
                    'password' => 'password123',
                ],
                'shop' => [
                    'name' => 'Tech Zone',
                    'description' => 'Your one-stop shop for all electronics and gadgets.',
                    'email' => 'sales@techzone.pk',
                    'phone' => '+92 321 9876543',
                    'whatsapp' => '+92 321 9876543',
                    'address' => 'Hall Road, Electronics Market',
                    'city' => 'Lahore',
                    'country' => 'Pakistan',
                    'currency' => 'PKR',
                ],
                'subscription_type' => 'subscribed', // Basic plan
                'plan' => $basicPlan,
                'categories' => ['Mobile Phones', 'Laptops', 'Accessories', 'Gaming'],
                'products_count' => 12,
            ],
            [
                'user' => [
                    'name' => 'Fatima Zahra',
                    'email' => 'fatima@demo.com',
                    'password' => 'password123',
                ],
                'shop' => [
                    'name' => 'Beauty Palace',
                    'description' => 'Authentic cosmetics and beauty products for all skin types.',
                    'email' => 'info@beautypalace.pk',
                    'phone' => '+92 333 5555555',
                    'whatsapp' => '+92 333 5555555',
                    'address' => 'F-7 Markaz',
                    'city' => 'Islamabad',
                    'country' => 'Pakistan',
                    'currency' => 'PKR',
                ],
                'subscription_type' => 'subscribed', // Pro plan
                'plan' => $proPlan,
                'categories' => ['Skincare', 'Makeup', 'Hair Care', 'Fragrances'],
                'products_count' => 20,
            ],
            [
                'user' => [
                    'name' => 'Usman Malik',
                    'email' => 'usman@demo.com',
                    'password' => 'password123',
                ],
                'shop' => [
                    'name' => 'Home Decor',
                    'description' => 'Beautiful home decoration items to make your house a home.',
                    'email' => 'hello@homedecor.pk',
                    'phone' => '+92 345 1111111',
                    'whatsapp' => '+92 345 1111111',
                    'address' => 'DHA Phase 5',
                    'city' => 'Lahore',
                    'country' => 'Pakistan',
                    'currency' => 'PKR',
                ],
                'subscription_type' => 'trial',
                'plan' => $trialPlan,
                'categories' => ['Wall Art', 'Lighting', 'Furniture'],
                'products_count' => 8,
            ],
            [
                'user' => [
                    'name' => 'Ayesha Siddiqui',
                    'email' => 'ayesha@demo.com',
                    'password' => 'password123',
                ],
                'shop' => [
                    'name' => 'Kids Corner',
                    'description' => 'Everything for your little ones - toys, clothes, and more.',
                    'email' => 'support@kidscorner.pk',
                    'phone' => '+92 312 2222222',
                    'whatsapp' => '+92 312 2222222',
                    'address' => 'Saddar, Near GPO',
                    'city' => 'Rawalpindi',
                    'country' => 'Pakistan',
                    'currency' => 'PKR',
                ],
                'subscription_type' => 'trial',
                'plan' => $trialPlan,
                'categories' => ['Toys', 'Baby Clothing', 'School Supplies'],
                'products_count' => 6,
            ],
        ];

        foreach ($demoShops as $data) {
            // Create user
            $user = User::firstOrCreate(
                ['email' => $data['user']['email']],
                [
                    'name' => $data['user']['name'],
                    'password' => Hash::make($data['user']['password']),
                    'status' => 1,
                    'is_admin' => false,
                    'email_verified_at' => now(),
                ]
            );

            // Create shop
            $shop = Shop::firstOrCreate(
                ['user_id' => $user->id],
                array_merge($data['shop'], [
                    'slug' => Str::slug($data['shop']['name']),
                    'logo' => 'https://picsum.photos/200/200?random=' . rand(1, 1000),
                    'banner' => 'https://picsum.photos/1200/400?random=' . rand(1, 1000),
                    'status' => 'active',
                    'subscription_status' => $data['subscription_type'] === 'trial' ? 'trial' : 'active',
                    'trial_ends_at' => $data['subscription_type'] === 'trial' ? Carbon::now()->addDays(30) : null,
                    'subscription_ends_at' => $data['subscription_type'] === 'subscribed' ? Carbon::now()->addYear() : null,
                ])
            );

            // Create subscription
            ShopSubscription::firstOrCreate(
                ['shop_id' => $shop->id],
                [
                    'plan_id' => $data['plan']->id,
                    'status' => $data['subscription_type'] === 'trial' ? 'trial' : 'active',
                    'starts_at' => Carbon::now(),
                    'ends_at' => $data['subscription_type'] === 'trial' 
                        ? Carbon::now()->addDays(30) 
                        : Carbon::now()->addYear(),
                    'trial_ends_at' => $data['subscription_type'] === 'trial' ? Carbon::now()->addDays(30) : null,
                    'amount_paid' => $data['subscription_type'] === 'trial' ? 0 : $data['plan']->price,
                ]
            );

            // Create categories
            foreach ($data['categories'] as $index => $categoryName) {
                $category = ShopCategory::firstOrCreate(
                    ['shop_id' => $shop->id, 'slug' => Str::slug($categoryName)],
                    [
                        'name' => $categoryName,
                        'description' => 'Browse our collection of ' . strtolower($categoryName),
                        'order' => $index,
                        'is_active' => true,
                    ]
                );

                // Create products for each category
                $productsPerCategory = ceil($data['products_count'] / count($data['categories']));
                for ($i = 1; $i <= $productsPerCategory; $i++) {
                    $productName = $this->generateProductName($categoryName, $i);
                    $price = rand(500, 10000);
                    
                    ShopProduct::firstOrCreate(
                        ['shop_id' => $shop->id, 'slug' => Str::slug($productName . '-' . $shop->slug . '-' . $category->slug . '-' . $i)],
                        [
                            'category_id' => $category->id,
                            'name' => $productName,
                            'sku' => strtoupper(substr($categoryName, 0, 3)) . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                            'short_description' => 'High quality ' . strtolower($productName) . ' available at best prices.',
                            'description' => 'Premium quality ' . strtolower($productName) . ' crafted with care. Perfect for everyday use. This product comes with a quality guarantee.',
                            'featured_image' => 'https://picsum.photos/400/400?random=' . rand(1, 1000),
                            'gallery_images' => json_encode([
                                'https://picsum.photos/400/400?random=' . rand(1001, 2000),
                                'https://picsum.photos/400/400?random=' . rand(2001, 3000),
                                'https://picsum.photos/400/400?random=' . rand(3001, 4000),
                            ]),
                            'price' => $price,
                            'compare_price' => $price * 1.2,
                            'quantity' => rand(10, 100),
                            'low_stock_threshold' => 5,
                            'track_inventory' => true,
                            'is_featured' => $i === 1,
                            'is_active' => true,
                            'is_taxable' => true,
                        ]
                    );
                }
            }

            $this->command->info("Created shop: {$shop->name} for user: {$user->name}");
        }

        $this->command->info('Demo shops seeded successfully!');
    }

    private function generateProductName(string $category, int $index): string
    {
        $products = [
            'Women\'s Wear' => ['Silk Dress', 'Cotton Kurti', 'Embroidered Suit', 'Casual Top', 'Designer Saree'],
            'Accessories' => ['Pearl Necklace', 'Gold Earrings', 'Leather Belt', 'Silk Scarf', 'Designer Watch'],
            'Footwear' => ['High Heels', 'Flat Sandals', 'Sports Shoes', 'Leather Boots', 'Casual Sneakers'],
            'Bags' => ['Leather Handbag', 'Clutch Purse', 'Backpack', 'Shoulder Bag', 'Tote Bag'],
            'Mobile Phones' => ['Smartphone Pro', 'Budget Phone', 'Gaming Phone', 'Camera Phone', 'Business Phone'],
            'Laptops' => ['Gaming Laptop', 'Business Laptop', 'Student Laptop', 'Ultrabook', 'Workstation'],
            'Gaming' => ['Gaming Mouse', 'Mechanical Keyboard', 'Gaming Headset', 'Controller', 'Gaming Chair'],
            'Skincare' => ['Face Cream', 'Serum', 'Face Wash', 'Sunscreen', 'Night Cream'],
            'Makeup' => ['Lipstick', 'Foundation', 'Mascara', 'Eye Shadow', 'Blush'],
            'Hair Care' => ['Shampoo', 'Conditioner', 'Hair Oil', 'Hair Serum', 'Hair Mask'],
            'Fragrances' => ['Perfume', 'Body Spray', 'Cologne', 'Attar', 'Gift Set'],
            'Wall Art' => ['Canvas Print', 'Photo Frame', 'Wall Clock', 'Mirror', 'Wall Hanging'],
            'Lighting' => ['Table Lamp', 'Floor Lamp', 'Chandelier', 'LED Lights', 'Wall Sconce'],
            'Furniture' => ['Coffee Table', 'Side Table', 'Bookshelf', 'Ottoman', 'Console Table'],
            'Toys' => ['Building Blocks', 'Doll House', 'Remote Car', 'Board Game', 'Puzzle Set'],
            'Baby Clothing' => ['Romper Set', 'Baby Dress', 'Onesie', 'Sleep Suit', 'Baby Cap'],
            'School Supplies' => ['Backpack', 'Pencil Box', 'Notebook Set', 'Lunch Box', 'Water Bottle'],
        ];

        $categoryProducts = $products[$category] ?? ['Product'];
        $productIndex = ($index - 1) % count($categoryProducts);
        
        return $categoryProducts[$productIndex];
    }
}
