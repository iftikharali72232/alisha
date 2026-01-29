<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopSubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopSubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = ShopSubscriptionPlan::withCount(['subscriptions'])
            ->ordered()
            ->get();

        return view('admin.shops.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.shops.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:shop_subscription_plans,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'trial_days' => 'required|integer|min:0|max:365',
            'max_products' => 'nullable|integer|min:-1',
            'max_gallery_images' => 'nullable|integer|min:0',
            'max_sliders' => 'nullable|integer|min:0',
            'max_categories' => 'nullable|integer|min:-1',
            'max_coupons' => 'nullable|integer|min:0',
            'max_images_per_product' => 'nullable|integer|min:0',
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'has_variations' => 'boolean',
            'has_offers' => 'boolean',
            'has_coupons' => 'boolean',
            'has_loyalty' => 'boolean',
            'has_reviews' => 'boolean',
            'has_custom_domain' => 'boolean',
            'has_analytics' => 'boolean',
            'has_priority_support' => 'boolean',
            'features_text' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'required|integer|min:0',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['has_variations'] = $request->boolean('has_variations');
        $validated['has_offers'] = $request->boolean('has_offers');
        $validated['has_coupons'] = $request->boolean('has_coupons');
        $validated['has_loyalty'] = $request->boolean('has_loyalty');
        $validated['has_reviews'] = $request->boolean('has_reviews');
        $validated['has_custom_domain'] = $request->boolean('has_custom_domain');
        $validated['has_analytics'] = $request->boolean('has_analytics');
        $validated['has_priority_support'] = $request->boolean('has_priority_support');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured');

        // Set legacy fields for backward compatibility
        $validated['loyalty_enabled'] = $validated['has_loyalty'];
        $validated['advanced_analytics'] = $validated['has_analytics'];
        $validated['custom_domain'] = $validated['has_custom_domain'];

        if ($request->filled('features_text')) {
            $validated['features'] = array_filter(array_map('trim', explode("\n", $request->features_text)));
        } else {
            $validated['features'] = [];
        }

        ShopSubscriptionPlan::create($validated);

        return redirect()->route('admin.shop-plans.index')
            ->with('success', 'Subscription plan created successfully!');
    }

    public function edit(ShopSubscriptionPlan $plan)
    {
        return view('admin.shops.plans.edit', compact('plan'));
    }

    public function update(Request $request, ShopSubscriptionPlan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:shop_subscription_plans,slug,' . $plan->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'trial_days' => 'required|integer|min:0|max:365',
            'max_products' => 'nullable|integer|min:-1',
            'max_gallery_images' => 'nullable|integer|min:0',
            'max_sliders' => 'nullable|integer|min:0',
            'max_categories' => 'nullable|integer|min:-1',
            'max_coupons' => 'nullable|integer|min:0',
            'max_images_per_product' => 'nullable|integer|min:0',
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'has_variations' => 'boolean',
            'has_offers' => 'boolean',
            'has_coupons' => 'boolean',
            'has_loyalty' => 'boolean',
            'has_reviews' => 'boolean',
            'has_custom_domain' => 'boolean',
            'has_analytics' => 'boolean',
            'has_priority_support' => 'boolean',
            'features_text' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'required|integer|min:0',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['has_variations'] = $request->boolean('has_variations');
        $validated['has_offers'] = $request->boolean('has_offers');
        $validated['has_coupons'] = $request->boolean('has_coupons');
        $validated['has_loyalty'] = $request->boolean('has_loyalty');
        $validated['has_reviews'] = $request->boolean('has_reviews');
        $validated['has_custom_domain'] = $request->boolean('has_custom_domain');
        $validated['has_analytics'] = $request->boolean('has_analytics');
        $validated['has_priority_support'] = $request->boolean('has_priority_support');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured');

        // Set legacy fields for backward compatibility
        $validated['loyalty_enabled'] = $validated['has_loyalty'];
        $validated['advanced_analytics'] = $validated['has_analytics'];
        $validated['custom_domain'] = $validated['has_custom_domain'];

        if ($request->filled('features_text')) {
            $validated['features'] = array_filter(array_map('trim', explode("\n", $request->features_text)));
        } else {
            $validated['features'] = [];
        }

        $plan->update($validated);

        return redirect()->route('admin.shop-plans.index')
            ->with('success', 'Subscription plan updated successfully!');
    }

    public function destroy(ShopSubscriptionPlan $plan)
    {
        if ($plan->subscriptions()->exists()) {
            return back()->with('error', 'Cannot delete plan with active subscriptions!');
        }

        $plan->delete();

        return redirect()->route('admin.shop-plans.index')
            ->with('success', 'Subscription plan deleted successfully!');
    }

    public function toggleStatus(ShopSubscriptionPlan $plan)
    {
        // Prevent deactivating plans with active subscriptions
        if ($plan->is_active && $plan->subscriptions()->where('status', 'active')->exists()) {
            return back()->with('error', 'Cannot deactivate this plan as it has active subscriptions. Please handle existing subscriptions first.');
        }

        $plan->update(['is_active' => !$plan->is_active]);

        return back()->with('success', 'Plan status updated successfully!');
    }
}
