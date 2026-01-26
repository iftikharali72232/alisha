<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopGlobalSetting;
use Illuminate\Http\Request;

class ShopGlobalSettingsController extends Controller
{
    public function index()
    {
        $settings = ShopGlobalSetting::all()->groupBy('group');
        
        // Get all settings with defaults
        $config = [
            'general' => [
                'shop_enabled' => ShopGlobalSetting::get('shop_enabled', true),
                'max_shops_per_user' => ShopGlobalSetting::get('max_shops_per_user', 1),
                'require_approval' => ShopGlobalSetting::get('require_approval', false),
                'default_currency' => ShopGlobalSetting::get('default_currency', 'PKR'),
                'whatsapp_support' => ShopGlobalSetting::get('whatsapp_support', true),
            ],
            'trial' => [
                'trial_enabled' => ShopGlobalSetting::get('trial_enabled', true),
                'trial_days' => ShopGlobalSetting::get('trial_days', 30),
                'trial_max_products' => ShopGlobalSetting::get('trial_max_products', 10),
                'trial_max_categories' => ShopGlobalSetting::get('trial_max_categories', 5),
                'trial_max_coupons' => ShopGlobalSetting::get('trial_max_coupons', 3),
                'trial_max_sliders' => ShopGlobalSetting::get('trial_max_sliders', 3),
                'trial_max_gallery' => ShopGlobalSetting::get('trial_max_gallery', 10),
                'trial_loyalty_enabled' => ShopGlobalSetting::get('trial_loyalty_enabled', false),
                'trial_analytics_enabled' => ShopGlobalSetting::get('trial_analytics_enabled', false),
                'trial_custom_domain' => ShopGlobalSetting::get('trial_custom_domain', false),
            ],
            'subscription' => [
                'subscription_enabled' => ShopGlobalSetting::get('subscription_enabled', true),
                'auto_suspend_expired' => ShopGlobalSetting::get('auto_suspend_expired', true),
                'grace_period_days' => ShopGlobalSetting::get('grace_period_days', 3),
                'send_expiry_reminder' => ShopGlobalSetting::get('send_expiry_reminder', true),
                'reminder_days_before' => ShopGlobalSetting::get('reminder_days_before', 7),
            ],
            'limits' => [
                'default_max_products' => ShopGlobalSetting::get('default_max_products', 50),
                'default_max_categories' => ShopGlobalSetting::get('default_max_categories', 20),
                'default_max_coupons' => ShopGlobalSetting::get('default_max_coupons', 10),
                'default_max_sliders' => ShopGlobalSetting::get('default_max_sliders', 10),
                'default_max_gallery' => ShopGlobalSetting::get('default_max_gallery', 50),
                'max_image_size_kb' => ShopGlobalSetting::get('max_image_size_kb', 2048),
            ],
            'features' => [
                'enable_reviews' => ShopGlobalSetting::get('enable_reviews', true),
                'enable_loyalty' => ShopGlobalSetting::get('enable_loyalty', true),
                'enable_coupons' => ShopGlobalSetting::get('enable_coupons', true),
                'enable_offers' => ShopGlobalSetting::get('enable_offers', true),
                'enable_customer_accounts' => ShopGlobalSetting::get('enable_customer_accounts', true),
                'enable_inventory_tracking' => ShopGlobalSetting::get('enable_inventory_tracking', true),
            ],
        ];

        return view('admin.shop-settings.index', compact('config'));
    }

    public function update(Request $request)
    {
        // General Settings
        ShopGlobalSetting::set('shop_enabled', $request->boolean('shop_enabled'), 'boolean', 'Enable shop feature globally', 'general');
        ShopGlobalSetting::set('max_shops_per_user', $request->input('max_shops_per_user', 1), 'integer', 'Maximum shops per user', 'general');
        ShopGlobalSetting::set('require_approval', $request->boolean('require_approval'), 'boolean', 'Require admin approval for new shops', 'general');
        ShopGlobalSetting::set('default_currency', $request->input('default_currency', 'PKR'), 'string', 'Default currency for shops', 'general');
        ShopGlobalSetting::set('whatsapp_support', $request->boolean('whatsapp_support'), 'boolean', 'Enable WhatsApp support widget', 'general');

        // Trial Settings
        ShopGlobalSetting::set('trial_enabled', $request->boolean('trial_enabled'), 'boolean', 'Enable trial period for new shops', 'trial');
        ShopGlobalSetting::set('trial_days', $request->input('trial_days', 30), 'integer', 'Trial period duration in days', 'trial');
        ShopGlobalSetting::set('trial_max_products', $request->input('trial_max_products', 10), 'integer', 'Maximum products during trial', 'trial');
        ShopGlobalSetting::set('trial_max_categories', $request->input('trial_max_categories', 5), 'integer', 'Maximum categories during trial', 'trial');
        ShopGlobalSetting::set('trial_max_coupons', $request->input('trial_max_coupons', 3), 'integer', 'Maximum coupons during trial', 'trial');
        ShopGlobalSetting::set('trial_max_sliders', $request->input('trial_max_sliders', 3), 'integer', 'Maximum sliders during trial', 'trial');
        ShopGlobalSetting::set('trial_max_gallery', $request->input('trial_max_gallery', 10), 'integer', 'Maximum gallery images during trial', 'trial');
        ShopGlobalSetting::set('trial_loyalty_enabled', $request->boolean('trial_loyalty_enabled'), 'boolean', 'Enable loyalty points during trial', 'trial');
        ShopGlobalSetting::set('trial_analytics_enabled', $request->boolean('trial_analytics_enabled'), 'boolean', 'Enable analytics during trial', 'trial');
        ShopGlobalSetting::set('trial_custom_domain', $request->boolean('trial_custom_domain'), 'boolean', 'Allow custom domain during trial', 'trial');

        // Subscription Settings
        ShopGlobalSetting::set('subscription_enabled', $request->boolean('subscription_enabled'), 'boolean', 'Enable subscription system', 'subscription');
        ShopGlobalSetting::set('auto_suspend_expired', $request->boolean('auto_suspend_expired'), 'boolean', 'Auto suspend expired subscriptions', 'subscription');
        ShopGlobalSetting::set('grace_period_days', $request->input('grace_period_days', 3), 'integer', 'Grace period after expiry in days', 'subscription');
        ShopGlobalSetting::set('send_expiry_reminder', $request->boolean('send_expiry_reminder'), 'boolean', 'Send expiry reminder emails', 'subscription');
        ShopGlobalSetting::set('reminder_days_before', $request->input('reminder_days_before', 7), 'integer', 'Days before expiry to send reminder', 'subscription');

        // Limits Settings
        ShopGlobalSetting::set('default_max_products', $request->input('default_max_products', 50), 'integer', 'Default max products for subscribed shops', 'limits');
        ShopGlobalSetting::set('default_max_categories', $request->input('default_max_categories', 20), 'integer', 'Default max categories', 'limits');
        ShopGlobalSetting::set('default_max_coupons', $request->input('default_max_coupons', 10), 'integer', 'Default max coupons', 'limits');
        ShopGlobalSetting::set('default_max_sliders', $request->input('default_max_sliders', 10), 'integer', 'Default max sliders', 'limits');
        ShopGlobalSetting::set('default_max_gallery', $request->input('default_max_gallery', 50), 'integer', 'Default max gallery images', 'limits');
        ShopGlobalSetting::set('max_image_size_kb', $request->input('max_image_size_kb', 2048), 'integer', 'Max image upload size in KB', 'limits');

        // Features Settings
        ShopGlobalSetting::set('enable_reviews', $request->boolean('enable_reviews'), 'boolean', 'Enable product reviews', 'features');
        ShopGlobalSetting::set('enable_loyalty', $request->boolean('enable_loyalty'), 'boolean', 'Enable loyalty points system', 'features');
        ShopGlobalSetting::set('enable_coupons', $request->boolean('enable_coupons'), 'boolean', 'Enable coupons feature', 'features');
        ShopGlobalSetting::set('enable_offers', $request->boolean('enable_offers'), 'boolean', 'Enable offers feature', 'features');
        ShopGlobalSetting::set('enable_customer_accounts', $request->boolean('enable_customer_accounts'), 'boolean', 'Enable customer accounts', 'features');
        ShopGlobalSetting::set('enable_inventory_tracking', $request->boolean('enable_inventory_tracking'), 'boolean', 'Enable inventory tracking', 'features');

        return redirect()->route('admin.shop-settings.index')->with('success', 'Shop global settings updated successfully!');
    }
}
