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
        Schema::table('shop_subscription_plans', function (Blueprint $table) {
            $table->decimal('commission_percentage', 5, 2)->default(10)->after('custom_domain');
            $table->boolean('has_variations')->default(false)->after('commission_percentage');
            $table->boolean('has_offers')->default(false)->after('has_variations');
            $table->boolean('has_coupons')->default(false)->after('has_offers');
            $table->boolean('has_loyalty')->default(false)->after('has_coupons');
            $table->boolean('has_reviews')->default(false)->after('has_loyalty');
            $table->boolean('has_analytics')->default(false)->after('has_reviews');
            $table->boolean('has_priority_support')->default(false)->after('has_analytics');
            $table->integer('max_images_per_product')->default(5)->after('max_coupons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_subscription_plans', function (Blueprint $table) {
            $table->dropColumn([
                'commission_percentage',
                'has_variations',
                'has_offers',
                'has_coupons',
                'has_loyalty',
                'has_reviews',
                'has_analytics',
                'has_priority_support',
                'max_images_per_product'
            ]);
        });
    }
};
