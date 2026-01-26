<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\ShopCategory;
use App\Models\ShopBrand;
use App\Models\ShopOffer;
use App\Models\ShopCoupon;
use App\Models\ShopSlider;
use App\Models\ShopGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopDashboardController extends Controller
{
    public function index()
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create')
                ->with('info', 'Please create your shop first!');
        }

        $shop->load('activeSubscription.plan');

        $stats = [
            'total_products' => $shop->products()->count(),
            'active_products' => $shop->products()->where('is_active', true)->count(),
            'total_orders' => $shop->orders()->count(),
            'pending_orders' => $shop->orders()->where('status', 'pending')->count(),
            'total_customers' => $shop->customers()->count(),
            'total_revenue' => $shop->orders()->where('status', 'delivered')->sum('total'),
            'today_orders' => $shop->orders()->whereDate('created_at', today())->count(),
            'today_revenue' => $shop->orders()->whereDate('created_at', today())->where('status', 'delivered')->sum('total'),
            'active_offers' => $shop->offers()->where('is_active', true)->where('starts_at', '<=', now())->where('ends_at', '>=', now())->count(),
            'active_coupons' => $shop->coupons()->where('is_active', true)->count(),
        ];

        $recentOrders = $shop->orders()->with('customer')->latest()->take(5)->get();
        $topProducts = $shop->products()
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(5)
            ->get();

        return view('user.shop.dashboard', compact('shop', 'stats', 'recentOrders', 'topProducts'));
    }

    public function create()
    {
        $existingShop = $this->getUserShop();
        
        if ($existingShop) {
            return redirect()->route('user.shop.dashboard')
                ->with('info', 'You already have a shop!');
        }

        return view('user.shop.create');
    }

    public function store(Request $request)
    {
        $existingShop = $this->getUserShop();
        
        if ($existingShop) {
            return redirect()->route('user.shop.dashboard')
                ->with('error', 'You already have a shop!');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['is_active'] = false; // Admin will approve

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('shops/logos', 'public');
        }

        $shop = Shop::create($validated);

        // Create trial subscription (30 days)
        $trialPlan = \App\Models\ShopSubscriptionPlan::where('price', 0)->first()
            ?? \App\Models\ShopSubscriptionPlan::first();

        if ($trialPlan) {
            $shop->subscriptions()->create([
                'plan_id' => $trialPlan->id,
                'status' => 'trial',
                'trial_starts_at' => now(),
                'trial_ends_at' => now()->addDays(30),
            ]);
        }

        // Create default loyalty settings
        $shop->loyaltySetting()->create([
            'points_per_currency' => 1,
            'points_value' => 0.01,
            'minimum_points_redemption' => 100,
            'maximum_discount_percentage' => 20,
            'points_expiry_days' => 365,
            'is_enabled' => true,
        ]);

        return redirect()->route('user.shop.dashboard')
            ->with('success', 'Shop created successfully! Your 30-day trial has started.');
    }

    public function settings()
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        $shop->load('activeSubscription.plan', 'loyaltySetting');

        return view('user.shop.settings', compact('shop'));
    }

    public function updateSettings(Request $request)
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:2048',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'theme_color' => 'nullable|string|max:7',
            'social_links' => 'nullable|array',
        ]);

        // Handle file uploads
        if ($request->hasFile('logo')) {
            if ($shop->logo) {
                Storage::disk('public')->delete($shop->logo);
            }
            $validated['logo'] = $request->file('logo')->store('shops/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            if ($shop->banner) {
                Storage::disk('public')->delete($shop->banner);
            }
            $validated['banner'] = $request->file('banner')->store('shops/banners', 'public');
        }

        $shop->update($validated);

        return back()->with('success', 'Shop settings updated successfully!');
    }

    public function subscription()
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        $shop->load(['activeSubscription.plan', 'subscriptions.plan']);
        $plans = \App\Models\ShopSubscriptionPlan::active()->ordered()->get();

        return view('user.shop.subscription', compact('shop', 'plans'));
    }

    protected function getUserShop(): ?Shop
    {
        return Shop::where('user_id', Auth::id())->first();
    }

    public function checkFeatureAccess(string $feature): bool
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return false;
        }

        return $shop->hasFeature($feature);
    }
}
