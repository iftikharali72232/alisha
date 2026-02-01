<?php
/**
 * Diagnostic script to debug shop issues on live server
 * Run: php test_live_debug.php
 * DELETE THIS FILE after debugging
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\ShopProduct;

echo "=== Live Server Diagnostic ===\n\n";

// Check shop
$shopSlug = 'kids-corner';
echo "1. Checking shop '{$shopSlug}'...\n";
$shop = Shop::where('slug', $shopSlug)->first();

if (!$shop) {
    echo "   ❌ Shop NOT FOUND!\n";
    exit;
}

echo "   ✓ Shop found:\n";
echo "     - ID: {$shop->id}\n";
echo "     - Name: {$shop->name}\n";
echo "     - Status: {$shop->status}\n";
echo "     - Subscription Status: {$shop->subscription_status}\n";
echo "     - Is Active (accessor): " . ($shop->is_active ? 'true' : 'false') . "\n\n";

if ($shop->status !== 'active') {
    echo "   ⚠️  ISSUE: Shop status is not 'active'! This causes 404 errors.\n";
    echo "      Fix: Run this SQL: UPDATE shops SET status = 'active' WHERE id = {$shop->id};\n\n";
}

// Check category
$categorySlug = 'school-supplies';
echo "2. Checking category '{$categorySlug}'...\n";
$category = ShopCategory::where('slug', $categorySlug)->first();

if (!$category) {
    echo "   ❌ Category NOT FOUND globally!\n\n";
} else {
    echo "   ✓ Category found:\n";
    echo "     - ID: {$category->id}\n";
    echo "     - Shop ID: {$category->shop_id}\n";
    echo "     - Is Active: " . ($category->is_active ? 'true' : 'false') . "\n";
    echo "     - Belongs to this shop: " . ($category->shop_id === $shop->id ? 'YES' : 'NO') . "\n\n";
    
    if ($category->shop_id !== $shop->id) {
        echo "   ⚠️  ISSUE: Category belongs to different shop!\n\n";
    }
    if (!$category->is_active) {
        echo "   ⚠️  ISSUE: Category is not active!\n\n";
    }
}

// Check products in this category
echo "3. Checking products in category...\n";
$products = ShopProduct::where('shop_id', $shop->id)
    ->where('category_id', $category->id ?? 0)
    ->where('is_active', true)
    ->get();

echo "   Found " . $products->count() . " active products\n\n";

// Check all categories for this shop
echo "4. All categories for this shop:\n";
$allCategories = ShopCategory::where('shop_id', $shop->id)->get();
foreach ($allCategories as $cat) {
    $productCount = ShopProduct::where('category_id', $cat->id)->where('is_active', true)->count();
    echo "   - {$cat->name} (slug: {$cat->slug}, active: " . ($cat->is_active ? 'Y' : 'N') . ", products: {$productCount})\n";
}

echo "\n=== End Diagnostic ===\n";
echo "Remember to delete this file after debugging!\n";
