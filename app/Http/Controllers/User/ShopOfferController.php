<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopOffer;
use App\Models\ShopProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopOfferController extends Controller
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

        // Check if shop has offers feature
        if (!$shop->hasFeature('has_offers')) {
            return redirect()->route('user.shop.subscription')
                ->with('error', 'Offers feature is not available in your current plan. Please upgrade to access this feature.');
        }

        $offers = $shop->offers()
            ->with('product')
            ->latest()
            ->paginate(15);

        return view('user.shop.offers.index', compact('shop', 'offers'));
    }

    public function create()
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        if (!$shop->hasFeature('has_offers')) {
            return redirect()->route('user.shop.subscription')
                ->with('error', 'Offers feature is not available in your current plan.');
        }

        $products = $shop->products()->where('is_active', true)->orderBy('name')->get();

        return view('user.shop.offers.create', compact('shop', 'products'));
    }

    public function store(Request $request)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || !$shop->hasFeature('has_offers')) {
            abort(403);
        }

        $validated = $request->validate([
            'product_id' => 'nullable|exists:shop_products,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'minimum_purchase' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
            'badge_text' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:7',
        ]);

        $validated['shop_id'] = $shop->id;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['badge_text'] = $validated['badge_text'] ?? ($validated['discount_type'] === 'percentage' 
            ? $validated['discount_value'] . '% OFF' 
            : 'Rs. ' . $validated['discount_value'] . ' OFF');

        ShopOffer::create($validated);

        return redirect()->route('user.shop.offers.index')
            ->with('success', 'Offer created successfully!');
    }

    public function edit(ShopOffer $offer)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $offer->shop_id !== $shop->id) {
            abort(404);
        }

        $products = $shop->products()->where('is_active', true)->orderBy('name')->get();

        return view('user.shop.offers.edit', compact('shop', 'offer', 'products'));
    }

    public function update(Request $request, ShopOffer $offer)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $offer->shop_id !== $shop->id) {
            abort(404);
        }

        $validated = $request->validate([
            'product_id' => 'nullable|exists:shop_products,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'minimum_purchase' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
            'badge_text' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:7',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $offer->update($validated);

        return redirect()->route('user.shop.offers.index')
            ->with('success', 'Offer updated successfully!');
    }

    public function destroy(ShopOffer $offer)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $offer->shop_id !== $shop->id) {
            abort(404);
        }

        $offer->delete();

        return redirect()->route('user.shop.offers.index')
            ->with('success', 'Offer deleted successfully!');
    }

    public function toggleStatus(ShopOffer $offer)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $offer->shop_id !== $shop->id) {
            abort(404);
        }

        $offer->update(['is_active' => !$offer->is_active]);

        return back()->with('success', 'Offer status updated!');
    }
}
