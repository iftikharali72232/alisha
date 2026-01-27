<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopCategoryController extends Controller
{
    protected function getUserShop(): ?Shop
    {
        return Shop::where('user_id', Auth::id())->first();
    }

    public function index()
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        $categories = $shop->categories()
            ->withCount('products')
            ->orderBy('order')
            ->orderBy('name')
            ->paginate(15);

        return view('user.shop.categories.index', compact('shop', 'categories'));
    }

    public function create()
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        // Check category limit
        $currentCount = $shop->categories()->count();
        $maxCategories = $shop->activeSubscription?->plan?->max_categories ?? 5;
        
        if ($maxCategories > 0 && $currentCount >= $maxCategories) {
            return redirect()->route('user.shop.categories.index')
                ->with('error', 'You have reached your category limit. Please upgrade your plan.');
        }

        $parentCategories = $shop->categories()->whereNull('parent_id')->orderBy('name')->get();

        return view('user.shop.categories.create', compact('shop', 'parentCategories'));
    }

    public function store(Request $request)
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:shop_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['shop_id'] = $shop->id;
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('shops/' . $shop->id . '/categories', 'public');
        }

        ShopCategory::create($validated);

        return redirect()->route('user.shop.categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function edit(ShopCategory $category)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $category->shop_id !== $shop->id) {
            abort(404);
        }

        $parentCategories = $shop->categories()
            ->whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('user.shop.categories.edit', compact('shop', 'category', 'parentCategories'));
    }

    public function update(Request $request, ShopCategory $category)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $category->shop_id !== $shop->id) {
            abort(404);
        }

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:shop_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')
                ->store('shops/' . $shop->id . '/categories', 'public');
        }

        $category->update($validated);

        return redirect()->route('user.shop.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(ShopCategory $category)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $category->shop_id !== $shop->id) {
            abort(404);
        }

        // Check if category has products
        if ($category->products()->exists()) {
            return back()->with('error', 'Cannot delete category with products. Please move or delete products first.');
        }

        // Delete image
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('user.shop.categories.index')
            ->with('success', 'Category deleted successfully!');
    }

    public function toggleStatus(ShopCategory $category)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $category->shop_id !== $shop->id) {
            abort(404);
        }

        $category->update(['is_active' => !$category->is_active]);

        return back()->with('success', 'Category status updated!');
    }
}
