<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Shops table
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('currency', 10)->default('PKR');
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->boolean('tax_included')->default(false);
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->enum('subscription_status', ['trial', 'active', 'expired', 'cancelled'])->default('trial');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->json('settings')->nullable(); // Store various shop settings
            $table->json('social_links')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });

        // Shop Sliders
        Schema::create('shop_sliders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image');
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Shop Gallery
        Schema::create('shop_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('image');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Product Categories
        Schema::create('shop_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('shop_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['shop_id', 'slug']);
        });

        // Brands
        Schema::create('shop_brands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['shop_id', 'slug']);
        });

        // Products
        Schema::create('shop_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('shop_categories')->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained('shop_brands')->onDelete('set null');
            $table->string('name');
            $table->string('slug');
            $table->string('sku')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('compare_price', 12, 2)->nullable(); // Original price for showing discount
            $table->decimal('cost_price', 12, 2)->nullable(); // Cost for profit calculation
            $table->integer('quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->boolean('track_inventory')->default(true);
            $table->boolean('allow_backorder')->default(false);
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('weight_unit', 10)->default('kg');
            $table->json('dimensions')->nullable(); // length, width, height
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_taxable')->default(true);
            $table->decimal('tax_rate', 5, 2)->nullable(); // Override shop tax rate
            $table->json('meta_data')->nullable(); // Additional product info
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('order_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['shop_id', 'slug']);
            $table->unique(['shop_id', 'sku']);
        });

        // Product Variations (Size, Color, etc.)
        Schema::create('shop_product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "Size", "Color"
            $table->string('type')->default('select'); // select, color, button
            $table->timestamps();
        });

        Schema::create('shop_product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained('shop_product_attributes')->onDelete('cascade');
            $table->string('value'); // e.g., "S", "M", "L" or "Red", "Blue"
            $table->string('color_code')->nullable(); // For color type
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Product Variants (combinations of attributes)
        Schema::create('shop_product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('shop_products')->onDelete('cascade');
            $table->string('sku')->nullable();
            $table->decimal('price', 12, 2)->nullable(); // Override product price
            $table->decimal('compare_price', 12, 2)->nullable();
            $table->integer('quantity')->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Variant attribute values pivot
        Schema::create('shop_variant_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')->constrained('shop_product_variants')->onDelete('cascade');
            $table->foreignId('attribute_value_id')->constrained('shop_product_attribute_values')->onDelete('cascade');
            $table->timestamps();
        });

        // Product Offers/Discounts
        Schema::create('shop_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 12, 2); // Discount value
            $table->decimal('min_order_amount', 12, 2)->nullable();
            $table->decimal('max_discount_amount', 12, 2)->nullable(); // Cap for percentage discounts
            $table->enum('applies_to', ['all', 'categories', 'products', 'brands'])->default('all');
            $table->json('applicable_ids')->nullable(); // IDs of categories/products/brands
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Coupon Codes
        Schema::create('shop_coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 12, 2);
            $table->decimal('min_order_amount', 12, 2)->nullable();
            $table->decimal('max_discount_amount', 12, 2)->nullable();
            $table->integer('usage_limit')->nullable(); // Total usage limit
            $table->integer('usage_limit_per_customer')->default(1);
            $table->integer('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['shop_id', 'code']);
        });

        // Shop Customers
        Schema::create('shop_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->integer('total_orders')->default(0);
            $table->decimal('total_spent', 12, 2)->default(0);
            $table->integer('loyalty_points')->default(0);
            $table->timestamp('last_order_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
            
            $table->unique(['shop_id', 'email']);
        });

        // Customer Addresses
        Schema::create('shop_customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('shop_customers')->onDelete('cascade');
            $table->string('label')->default('Home'); // Home, Office, etc.
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Orders
        Schema::create('shop_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('shop_customers')->onDelete('set null');
            $table->string('order_number')->unique();
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('shipping_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('coupon_code')->nullable();
            $table->integer('loyalty_points_used')->default(0);
            $table->decimal('loyalty_discount', 12, 2)->default(0);
            $table->integer('loyalty_points_earned')->default(0);
            // Billing info
            $table->string('billing_name');
            $table->string('billing_email');
            $table->string('billing_phone')->nullable();
            $table->text('billing_address');
            $table->string('billing_city');
            $table->string('billing_state')->nullable();
            $table->string('billing_postal_code')->nullable();
            $table->string('billing_country');
            // Shipping info
            $table->string('shipping_name')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_country')->nullable();
            // Additional
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        // Coupon Usage History
        Schema::create('shop_coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('shop_coupons')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('shop_customers')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('shop_orders')->onDelete('set null');
            $table->decimal('discount_amount', 12, 2);
            $table->timestamps();
        });

        // Order Items
        Schema::create('shop_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('shop_orders')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('shop_products')->onDelete('set null');
            $table->foreignId('variant_id')->nullable()->constrained('shop_product_variants')->onDelete('set null');
            $table->string('product_name');
            $table->string('variant_name')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('quantity');
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->json('options')->nullable(); // Selected variant options
            $table->timestamps();
        });

        // Loyalty Program Settings
        Schema::create('shop_loyalty_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->unique()->constrained()->onDelete('cascade');
            $table->boolean('is_enabled')->default(false);
            $table->decimal('points_per_currency', 8, 2)->default(1); // e.g., 1 point per 100 PKR
            $table->decimal('currency_per_point', 8, 2)->default(100); // How much spend for 1 point
            $table->decimal('redemption_rate', 8, 2)->default(1); // 1 point = 1 PKR discount
            $table->integer('min_points_to_redeem')->default(100);
            $table->integer('max_points_per_order')->nullable(); // Max points that can be used per order
            $table->integer('points_validity_days')->nullable(); // Points expire after X days
            $table->timestamps();
        });

        // Customer Loyalty Points History
        Schema::create('shop_loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('shop_customers')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('shop_orders')->onDelete('set null');
            $table->enum('type', ['earned', 'redeemed', 'expired', 'adjusted']);
            $table->integer('points');
            $table->decimal('order_amount', 12, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // Product Reviews
        Schema::create('shop_product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('shop_products')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('shop_customers')->onDelete('set null');
            $table->foreignId('order_id')->nullable()->constrained('shop_orders')->onDelete('set null');
            $table->string('name');
            $table->string('email');
            $table->tinyInteger('rating'); // 1-5
            $table->string('title')->nullable();
            $table->text('review')->nullable();
            $table->json('images')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_reply')->nullable();
            $table->boolean('is_verified_purchase')->default(false);
            $table->timestamps();
        });

        // Subscription Plans (managed by super admin)
        Schema::create('shop_subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->integer('trial_days')->default(30);
            $table->integer('max_products')->nullable(); // null = unlimited
            $table->integer('max_gallery_images')->nullable();
            $table->integer('max_sliders')->nullable();
            $table->integer('max_categories')->nullable();
            $table->integer('max_coupons')->nullable();
            $table->boolean('loyalty_enabled')->default(false);
            $table->boolean('advanced_analytics')->default(false);
            $table->boolean('custom_domain')->default(false);
            $table->json('features')->nullable(); // Additional features
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Shop Subscriptions
        Schema::create('shop_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('shop_subscription_plans')->onDelete('cascade');
            $table->enum('status', ['trial', 'active', 'expired', 'cancelled'])->default('trial');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->timestamp('trial_ends_at')->nullable();
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamps();
        });

        // Global Shop Settings (managed by super admin)
        Schema::create('shop_global_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, number, boolean, json
            $table->string('group')->default('general');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_global_settings');
        Schema::dropIfExists('shop_subscriptions');
        Schema::dropIfExists('shop_subscription_plans');
        Schema::dropIfExists('shop_product_reviews');
        Schema::dropIfExists('shop_loyalty_transactions');
        Schema::dropIfExists('shop_loyalty_settings');
        Schema::dropIfExists('shop_order_items');
        Schema::dropIfExists('shop_orders');
        Schema::dropIfExists('shop_customer_addresses');
        Schema::dropIfExists('shop_customers');
        Schema::dropIfExists('shop_coupon_usages');
        Schema::dropIfExists('shop_coupons');
        Schema::dropIfExists('shop_offers');
        Schema::dropIfExists('shop_variant_attributes');
        Schema::dropIfExists('shop_product_variants');
        Schema::dropIfExists('shop_product_attribute_values');
        Schema::dropIfExists('shop_product_attributes');
        Schema::dropIfExists('shop_products');
        Schema::dropIfExists('shop_brands');
        Schema::dropIfExists('shop_categories');
        Schema::dropIfExists('shop_galleries');
        Schema::dropIfExists('shop_sliders');
        Schema::dropIfExists('shops');
    }
};
