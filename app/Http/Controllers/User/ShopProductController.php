<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\ShopCategory;
use App\Models\ShopBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopProductController extends Controller
{
    protected function getUserShop(): ?Shop
    {
        return Shop::where('user_id', Auth::id())->first();
    }

    public function index(Request $request)
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        $query = $shop->products()->with(['category', 'brand', 'orderItems']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === '1');
        }

        $products = $query->latest()->paginate(15);
        $categories = $shop->categories()->orderBy('name')->get();

        return view('user.shop.products.index', compact('shop', 'products', 'categories'));
    }

    public function create()
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        // Check product limit
        $currentCount = $shop->products()->count();
        $maxProducts = $shop->activeSubscription?->plan?->max_products ?? 10;
        
        if ($maxProducts > 0 && $currentCount >= $maxProducts) {
            return redirect()->route('user.shop.products.index')
                ->with('error', 'You have reached your product limit. Please upgrade your plan to add more products.');
        }

        $categories = $shop->categories()->orderBy('name')->get();
        $brands = $shop->brands()->orderBy('name')->get();

        return view('user.shop.products.create', compact('shop', 'categories', 'brands'));
    }

    public function store(Request $request)
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        $validated = $request->validate([
            'category_id' => 'nullable|exists:shop_categories,id',
            'brand_id' => 'nullable|exists:shop_brands,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'featured_image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_digital' => 'boolean',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['shop_id'] = $shop->id;
        $validated['is_active'] = $request->boolean('is_active');
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['sku'] = $validated['sku'] ?? strtoupper(Str::random(8));
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_digital'] = $request->boolean('is_digital');

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('shops/' . $shop->id . '/products', 'public');
        }

        // Handle multiple images
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('shops/' . $shop->id . '/products', 'public');
            }
        }
        $validated['images'] = $images;

        // Remove file inputs from validated data
        unset($validated['featured_image_file'], $validated['images_files']);

        ShopProduct::create($validated);

        return redirect()->route('user.shop.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function show(ShopProduct $product)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $product->shop_id !== $shop->id) {
            abort(404);
        }

        $product->load(['category', 'brand', 'variants', 'reviews']);

        return view('user.shop.products.show', compact('shop', 'product'));
    }

    public function edit(ShopProduct $product)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $product->shop_id !== $shop->id) {
            abort(404);
        }

        $categories = $shop->categories()->orderBy('name')->get();
        $brands = $shop->brands()->orderBy('name')->get();

        return view('user.shop.products.edit', compact('shop', 'product', 'categories', 'brands'));
    }

    public function update(Request $request, ShopProduct $product)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $product->shop_id !== $shop->id) {
            abort(404);
        }

        $validated = $request->validate([
            'category_id' => 'nullable|exists:shop_categories,id',
            'brand_id' => 'nullable|exists:shop_brands,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'featured_image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_digital' => 'boolean',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_digital'] = $request->boolean('is_digital');

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            if ($product->featured_image) {
                Storage::disk('public')->delete($product->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')
                ->store('shops/' . $shop->id . '/products', 'public');
        }

        // Handle multiple images
        if ($request->hasFile('images')) {
            // Keep existing images unless explicitly removed
            $images = $product->images ?? [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('shops/' . $shop->id . '/products', 'public');
            }
            $validated['images'] = $images;
        }

        $product->update($validated);

        return redirect()->route('user.shop.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(ShopProduct $product)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $product->shop_id !== $shop->id) {
            abort(404);
        }

        // Delete associated images
        if ($product->featured_image) {
            Storage::disk('public')->delete($product->featured_image);
        }
        
        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('user.shop.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    public function toggleStatus(ShopProduct $product)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $product->shop_id !== $shop->id) {
            abort(404);
        }

        $product->update(['is_active' => !$product->is_active]);

        return back()->with('success', 'Product status updated!');
    }

    public function deleteImage(Request $request, ShopProduct $product)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $product->shop_id !== $shop->id) {
            abort(404);
        }

        $imageIndex = $request->input('index');
        $images = $product->images ?? [];

        if (isset($images[$imageIndex])) {
            Storage::disk('public')->delete($images[$imageIndex]);
            unset($images[$imageIndex]);
            $product->update(['images' => array_values($images)]);
        }

        return back()->with('success', 'Image deleted successfully!');
    }
}
