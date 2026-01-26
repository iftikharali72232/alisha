<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopSubscription;
use App\Models\ShopSubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Shop::with(['user', 'activeSubscription.plan'])
            ->withCount(['products', 'orders', 'customers']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('slug', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($u) use ($request) {
                        $u->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('subscription_status')) {
            $query->whereHas('activeSubscription', function ($q) use ($request) {
                $q->where('status', $request->subscription_status);
            });
        }

        $shops = $query->latest()->paginate(15);

        return view('admin.shops.index', compact('shops'));
    }

    public function create()
    {
        $users = User::where('status', 'active')->orderBy('name')->get();
        $plans = ShopSubscriptionPlan::active()->ordered()->get();
        
        return view('admin.shops.create', compact('users', 'plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:shops,slug',
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
            'plan_id' => 'required|exists:shop_subscription_plans,id',
            'trial_days' => 'nullable|integer|min:0|max:365',
            'status' => 'required|in:active,inactive,suspended,pending',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        // Handle file uploads
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('shops/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('shops/banners', 'public');
        }

        // Remove plan_id and trial_days from shop data
        $planId = $validated['plan_id'];
        $trialDays = $validated['trial_days'] ?? 30;
        unset($validated['plan_id'], $validated['trial_days']);

        $shop = Shop::create($validated);

        // Create subscription
        $plan = ShopSubscriptionPlan::find($planId);
        $subscription = ShopSubscription::create([
            'shop_id' => $shop->id,
            'plan_id' => $planId,
            'status' => 'trial',
            'trial_starts_at' => now(),
            'trial_ends_at' => now()->addDays($trialDays),
        ]);

        // Create default loyalty settings
        $shop->loyaltySetting()->create([
            'points_per_currency' => 1,
            'points_value' => 0.01,
            'minimum_points_redemption' => 100,
            'maximum_discount_percentage' => 20,
            'points_expiry_days' => 365,
            'signup_bonus_points' => 50,
            'review_bonus_points' => 10,
            'referral_bonus_points' => 100,
            'birthday_bonus_points' => 50,
            'is_enabled' => true,
        ]);

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop created successfully with ' . $trialDays . ' days trial!');
    }

    public function show(Shop $shop)
    {
        $shop->load([
            'user',
            'activeSubscription.plan',
            'products' => fn($q) => $q->latest()->take(5),
            'orders' => fn($q) => $q->latest()->take(5),
            'customers' => fn($q) => $q->latest()->take(5),
        ]);

        $stats = [
            'total_products' => $shop->products()->count(),
            'total_orders' => $shop->orders()->count(),
            'total_customers' => $shop->customers()->count(),
            'total_revenue' => $shop->orders()->where('status', 'delivered')->sum('total'),
            'pending_orders' => $shop->orders()->where('status', 'pending')->count(),
            'active_offers' => $shop->offers()->active()->count(),
        ];

        return view('admin.shops.show', compact('shop', 'stats'));
    }

    public function edit(Shop $shop)
    {
        $users = User::where('status', 'active')->orderBy('name')->get();
        $plans = ShopSubscriptionPlan::active()->ordered()->get();
        $shop->load('activeSubscription.plan');
        
        return view('admin.shops.edit', compact('shop', 'users', 'plans'));
    }

    public function update(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:shops,slug,' . $shop->id,
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
            'status' => 'required|in:active,inactive,suspended,pending',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        // Handle file uploads
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($shop->logo) {
                \Storage::disk('public')->delete($shop->logo);
            }
            $validated['logo'] = $request->file('logo')->store('shops/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            // Delete old banner
            if ($shop->banner) {
                \Storage::disk('public')->delete($shop->banner);
            }
            $validated['banner'] = $request->file('banner')->store('shops/banners', 'public');
        }

        $shop->update($validated);

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop updated successfully!');
    }

    public function destroy(Shop $shop)
    {
        // Soft delete shop and related data
        $shop->delete();

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop deleted successfully!');
    }

    public function manageSubscription(Shop $shop)
    {
        $plans = ShopSubscriptionPlan::active()->ordered()->get();
        $shop->load(['subscriptions.plan', 'activeSubscription.plan']);
        
        return view('admin.shops.subscription', compact('shop', 'plans'));
    }

    public function updateSubscription(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:shop_subscription_plans,id',
            'action' => 'required|in:activate,extend,cancel,start_trial',
            'days' => 'nullable|integer|min:1|max:365',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'payment_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $plan = ShopSubscriptionPlan::find($validated['plan_id']);
        $subscription = $shop->activeSubscription;

        switch ($validated['action']) {
            case 'start_trial':
                // Cancel existing subscription if any
                if ($subscription) {
                    $subscription->cancel();
                }
                
                ShopSubscription::create([
                    'shop_id' => $shop->id,
                    'plan_id' => $plan->id,
                    'status' => 'trial',
                    'trial_starts_at' => now(),
                    'trial_ends_at' => now()->addDays($validated['days'] ?? 30),
                    'notes' => $validated['notes'],
                ]);
                $message = 'Trial started for ' . ($validated['days'] ?? 30) . ' days!';
                break;

            case 'activate':
                if ($subscription) {
                    $subscription->cancel();
                }

                $days = $validated['days'] ?? ($plan->billing_period === 'year' ? 365 : 30);
                
                ShopSubscription::create([
                    'shop_id' => $shop->id,
                    'plan_id' => $plan->id,
                    'status' => 'active',
                    'starts_at' => now(),
                    'ends_at' => now()->addDays($days),
                    'amount_paid' => $validated['amount_paid'] ?? $plan->price,
                    'payment_method' => $validated['payment_method'],
                    'payment_reference' => $validated['payment_reference'],
                    'notes' => $validated['notes'],
                ]);
                $message = 'Subscription activated for ' . $days . ' days!';
                break;

            case 'extend':
                if (!$subscription) {
                    return back()->with('error', 'No active subscription to extend!');
                }

                $days = $validated['days'] ?? 30;
                $newEndDate = $subscription->ends_at 
                    ? $subscription->ends_at->addDays($days)
                    : now()->addDays($days);

                $subscription->update([
                    'ends_at' => $newEndDate,
                    'notes' => $subscription->notes . "\n[Extended by $days days on " . now()->format('Y-m-d') . "]",
                ]);
                $message = 'Subscription extended by ' . $days . ' days!';
                break;

            case 'cancel':
                if ($subscription) {
                    $subscription->cancel();
                }
                $message = 'Subscription cancelled!';
                break;
        }

        return redirect()->route('admin.shops.subscription', $shop)
            ->with('success', $message);
    }

    public function toggleStatus(Shop $shop)
    {
        $newStatus = $shop->status === 'active' ? 'inactive' : 'active';
        $shop->update(['status' => $newStatus]);

        return back()->with('success', 'Shop status updated to ' . $newStatus);
    }
}
