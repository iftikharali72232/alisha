<?php
/**
 * Diagnostic script to debug shop issues on server
 *
 * Usage:
 *   php test_live_debug.php                   # lists all shops + product/category counts
 *   php test_live_debug.php kids-corner       # detailed check for one shop
 *   php test_live_debug.php kids-corner toys  # detailed check for shop + category slug
 *
 * DELETE THIS FILE after debugging.
 */

use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\ShopProduct;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

echo "=== Shop Diagnostic ===\n\n";
echo "APP_URL: " . (config('app.url') ?? 'n/a') . "\n";
echo "ENV: " . (app()->environment() ? implode(',', (array) app()->environment()) : 'n/a') . "\n\n";

$shopSlug = $argv[1] ?? null;
$categorySlug = $argv[2] ?? 'school-supplies';

if (!$shopSlug) {
    echo "No shop slug provided. Showing summary for all shops.\n\n";
    $shops = Shop::select('id', 'slug', 'name', 'status', 'subscription_status')->orderBy('id')->get();

    foreach ($shops as $shop) {
        $activeProducts = ShopProduct::where('shop_id', $shop->id)->where('is_active', true)->count();
        $activeCategories = ShopCategory::where('shop_id', $shop->id)->where('is_active', true)->count();
        $note = $shop->status === 'active' ? '' : " ⚠ status={$shop->status}";
        echo "- [{$shop->id}] {$shop->slug} | {$shop->name} | shop_status={$shop->status} | sub={$shop->subscription_status} | active_categories={$activeCategories} | active_products={$activeProducts}{$note}\n";
    }

    echo "\nIf active_products=0 for all shops on LIVE, DB data or is_active flags are the cause.\n";
    echo "If data is OK but website still shows none, deployment/cache/config is the cause.\n\n";
    echo "=== End Diagnostic ===\n";
    echo "Remember to delete this file after debugging!\n";
    exit;
}

echo "1) Checking shop '{$shopSlug}'...\n";
$shop = Shop::where('slug', $shopSlug)->first();
if (!$shop) {
    echo "   ❌ Shop NOT FOUND\n";
    echo "=== End Diagnostic ===\n";
    exit(1);
}

echo "   ✓ Shop found:\n";
echo "     - ID: {$shop->id}\n";
echo "     - Name: {$shop->name}\n";
echo "     - Status: {$shop->status}\n";
echo "     - Subscription Status: {$shop->subscription_status}\n";
echo "\n";

if ($shop->status !== 'active') {
    echo "   ⚠ ISSUE: Shop status is not 'active' => storefront will 404.\n";
    echo "     Fix SQL: UPDATE shops SET status='active' WHERE id={$shop->id};\n\n";
}

echo "2) Checking category '{$categorySlug}' (global lookup + ownership)...\n";
$category = ShopCategory::where('slug', $categorySlug)->first();

if (!$category) {
    echo "   ❌ Category NOT FOUND globally\n\n";
} else {
    echo "   ✓ Category found:\n";
    echo "     - ID: {$category->id}\n";
    echo "     - Shop ID: {$category->shop_id}\n";
    echo "     - Is Active: " . ($category->is_active ? 'true' : 'false') . "\n";
    echo "     - Belongs to this shop: " . ($category->shop_id === $shop->id ? 'YES' : 'NO') . "\n\n";
}

echo "3) Checking products in this category (shop_id + category_id + is_active=1)...\n";
$products = ShopProduct::query()
    ->where('shop_id', $shop->id)
    ->when($category, fn($q) => $q->where('category_id', $category->id))
    ->where('is_active', true)
    ->get(['id', 'slug', 'name', 'is_active']);

echo "   Found " . $products->count() . " active products\n";
foreach ($products->take(10) as $p) {
    echo "   - [{$p->id}] {$p->slug} | {$p->name}\n";
}
if ($products->count() > 10) {
    echo "   ..." . ($products->count() - 10) . " more\n";
}
echo "\n";

echo "4) All categories for this shop:\n";
$allCategories = ShopCategory::where('shop_id', $shop->id)->orderBy('name')->get(['id', 'name', 'slug', 'is_active']);
foreach ($allCategories as $cat) {
    $productCount = ShopProduct::where('shop_id', $shop->id)
        ->where('category_id', $cat->id)
        ->where('is_active', true)
        ->count();
    echo "   - {$cat->name} (slug: {$cat->slug}, active: " . ($cat->is_active ? 'Y' : 'N') . ", active_products: {$productCount})\n";
}

echo "\n=== End Diagnostic ===\n";
echo "Remember to delete this file after debugging!\n";
