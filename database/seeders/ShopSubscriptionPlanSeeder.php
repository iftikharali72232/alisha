<?php

namespace Database\Seeders;

use App\Models\ShopSubscriptionPlan;
use Illuminate\Database\Seeder;

class ShopSubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        // Free Trial Plan
        ShopSubscriptionPlan::create([
            'name' => 'Free Trial',
            'slug' => 'free-trial',
            'description' => 'Start your shop with a 30-day free trial. Experience basic features.',
            'price' => 0,
            'billing_cycle' => 'monthly',
            'trial_days' => 30,
            'max_products' => 10,
            'max_gallery_images' => 10,
            'max_sliders' => 2,
            'max_categories' => 3,
            'max_coupons' => 0,
            'loyalty_enabled' => false,
            'advanced_analytics' => false,
            'custom_domain' => false,
            'features' => [
                'Basic shop setup',
                'Up to 10 products',
                '3 categories',
                'Basic product listing',
                'Order management',
                'WhatsApp support widget',
            ],
            'is_active' => true,
            'is_featured' => false,
            'order' => 1,
        ]);

        // Basic Plan
        ShopSubscriptionPlan::create([
            'name' => 'Basic',
            'slug' => 'basic',
            'description' => 'Perfect for small businesses starting out.',
            'price' => 999,
            'billing_cycle' => 'monthly',
            'trial_days' => 7,
            'max_products' => 50,
            'max_gallery_images' => 50,
            'max_sliders' => 5,
            'max_categories' => 10,
            'max_coupons' => 5,
            'loyalty_enabled' => false,
            'advanced_analytics' => false,
            'custom_domain' => false,
            'features' => [
                'Up to 50 products',
                '10 categories',
                '5 coupons',
                'Time-limited offers',
                'Customer reviews',
                'Order management',
                'WhatsApp support widget',
            ],
            'is_active' => true,
            'is_featured' => false,
            'order' => 2,
        ]);

        // Professional Plan
        ShopSubscriptionPlan::create([
            'name' => 'Professional',
            'slug' => 'professional',
            'description' => 'For growing businesses with advanced needs.',
            'price' => 2499,
            'billing_cycle' => 'monthly',
            'trial_days' => 7,
            'max_products' => 200,
            'max_gallery_images' => 200,
            'max_sliders' => 10,
            'max_categories' => 25,
            'max_coupons' => 20,
            'loyalty_enabled' => true,
            'advanced_analytics' => true,
            'custom_domain' => false,
            'features' => [
                'Up to 200 products',
                '25 categories',
                '20 coupons',
                'Loyalty points system',
                'Advanced analytics',
                'Customer reviews',
                'Order management',
                'Customer management',
                'WhatsApp support widget',
            ],
            'is_active' => true,
            'is_featured' => true,
            'order' => 3,
        ]);

        // Premium Plan
        ShopSubscriptionPlan::create([
            'name' => 'Premium',
            'slug' => 'premium',
            'description' => 'Unlimited features for established businesses.',
            'price' => 4999,
            'billing_cycle' => 'monthly',
            'trial_days' => 14,
            'max_products' => null, // Unlimited
            'max_gallery_images' => null,
            'max_sliders' => null,
            'max_categories' => null,
            'max_coupons' => null,
            'loyalty_enabled' => true,
            'advanced_analytics' => true,
            'custom_domain' => true,
            'features' => [
                'Unlimited products',
                'Unlimited categories',
                'Unlimited coupons',
                'Loyalty points system',
                'Advanced analytics',
                'Custom domain support',
                'Priority support',
                'Order management',
                'Customer management',
                'WhatsApp support widget',
                'Export reports',
            ],
            'is_active' => true,
            'is_featured' => false,
            'order' => 4,
        ]);

        // Enterprise (Yearly)
        ShopSubscriptionPlan::create([
            'name' => 'Enterprise',
            'slug' => 'enterprise',
            'description' => 'Best value for large businesses. Save 20% with yearly billing.',
            'price' => 47990, // ~20% discount on Premium yearly
            'billing_cycle' => 'yearly',
            'trial_days' => 30,
            'max_products' => null,
            'max_gallery_images' => null,
            'max_sliders' => null,
            'max_categories' => null,
            'max_coupons' => null,
            'loyalty_enabled' => true,
            'advanced_analytics' => true,
            'custom_domain' => true,
            'features' => [
                'All Premium features',
                'Unlimited everything',
                'Dedicated account manager',
                'Priority onboarding',
                'Custom integrations',
                '24/7 support',
            ],
            'is_active' => true,
            'is_featured' => false,
            'order' => 5,
        ]);
    }
}
