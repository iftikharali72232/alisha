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
            'loyalty_enabled' => 'boolean',
            'advanced_analytics' => 'boolean',
            'custom_domain' => 'boolean',
            'features_text' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'required|integer|min:0',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['loyalty_enabled'] = $request->boolean('loyalty_enabled');
        $validated['advanced_analytics'] = $request->boolean('advanced_analytics');
        $validated['custom_domain'] = $request->boolean('custom_domain');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured');

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
            'loyalty_enabled' => 'boolean',
            'advanced_analytics' => 'boolean',
            'custom_domain' => 'boolean',
            'features_text' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'required|integer|min:0',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['loyalty_enabled'] = $request->boolean('loyalty_enabled');
        $validated['advanced_analytics'] = $request->boolean('advanced_analytics');
        $validated['custom_domain'] = $request->boolean('custom_domain');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured');

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
        $plan->update(['is_active' => !$plan->is_active]);

        return back()->with('success', 'Plan status updated!');
    }
}
