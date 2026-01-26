<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ShopCouponController extends Controller
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

        // Check if shop has coupons feature
        if (!$shop->hasFeature('has_coupons')) {
            return redirect()->route('user.shop.subscription')
                ->with('error', 'Coupons feature is not available in your current plan. Please upgrade to access this feature.');
        }

        $coupons = $shop->coupons()
            ->withCount('usages')
            ->latest()
            ->paginate(15);

        return view('user.shop.coupons.index', compact('shop', 'coupons'));
    }

    public function create()
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        if (!$shop->hasFeature('has_coupons')) {
            return redirect()->route('user.shop.subscription')
                ->with('error', 'Coupons feature is not available in your current plan.');
        }

        return view('user.shop.coupons.create', compact('shop'));
    }

    public function store(Request $request)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || !$shop->hasFeature('has_coupons')) {
            abort(403);
        }

        $validated = $request->validate([
            'code' => 'nullable|string|max:50|unique:shop_coupons,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed,free_shipping',
            'value' => 'required|numeric|min:0',
            'minimum_purchase' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'usage_limit_per_customer' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
        ]);

        $validated['shop_id'] = $shop->id;
        $validated['code'] = $validated['code'] ?? strtoupper(Str::random(8));
        $validated['is_active'] = $request->boolean('is_active', true);

        ShopCoupon::create($validated);

        return redirect()->route('user.shop.coupons.index')
            ->with('success', 'Coupon created successfully!');
    }

    public function show(ShopCoupon $coupon)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $coupon->shop_id !== $shop->id) {
            abort(404);
        }

        $coupon->load(['usages.customer', 'usages.order']);

        return view('user.shop.coupons.show', compact('shop', 'coupon'));
    }

    public function edit(ShopCoupon $coupon)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $coupon->shop_id !== $shop->id) {
            abort(404);
        }

        return view('user.shop.coupons.edit', compact('shop', 'coupon'));
    }

    public function update(Request $request, ShopCoupon $coupon)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $coupon->shop_id !== $shop->id) {
            abort(404);
        }

        $validated = $request->validate([
            'code' => 'nullable|string|max:50|unique:shop_coupons,code,' . $coupon->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed,free_shipping',
            'value' => 'required|numeric|min:0',
            'minimum_purchase' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'usage_limit_per_customer' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $coupon->update($validated);

        return redirect()->route('user.shop.coupons.index')
            ->with('success', 'Coupon updated successfully!');
    }

    public function destroy(ShopCoupon $coupon)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $coupon->shop_id !== $shop->id) {
            abort(404);
        }

        $coupon->delete();

        return redirect()->route('user.shop.coupons.index')
            ->with('success', 'Coupon deleted successfully!');
    }

    public function toggleStatus(ShopCoupon $coupon)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $coupon->shop_id !== $shop->id) {
            abort(404);
        }

        $coupon->update(['is_active' => !$coupon->is_active]);

        return back()->with('success', 'Coupon status updated!');
    }

    public function generateCode()
    {
        return response()->json([
            'code' => strtoupper(Str::random(8)),
        ]);
    }
}
