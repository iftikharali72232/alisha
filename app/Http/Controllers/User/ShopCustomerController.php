<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopCustomerController extends Controller
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

        $query = $shop->customers()
            ->withCount('orders')
            ->withSum(['orders' => fn($q) => $q->where('status', 'delivered')], 'total');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->latest()->paginate(15);

        $stats = [
            'total' => $shop->customers()->count(),
            'new_this_month' => $shop->customers()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'with_orders' => $shop->customers()->whereHas('orders')->count(),
            'total_points' => $shop->customers()->sum('loyalty_points'),
        ];

        return view('user.shop.customers.index', compact('shop', 'customers', 'stats'));
    }

    public function show(ShopCustomer $customer)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $customer->shop_id !== $shop->id) {
            abort(404);
        }

        $customer->load([
            'orders' => fn($q) => $q->latest()->take(10),
            'addresses',
            'loyaltyTransactions' => fn($q) => $q->latest()->take(10),
        ]);

        $stats = [
            'total_orders' => $customer->orders()->count(),
            'total_spent' => $customer->orders()->where('status', 'delivered')->sum('total'),
            'loyalty_points' => $customer->loyalty_points,
            'average_order_value' => $customer->orders()->where('status', 'delivered')->avg('total') ?? 0,
        ];

        return view('user.shop.customers.show', compact('shop', 'customer', 'stats'));
    }

    public function edit(ShopCustomer $customer)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $customer->shop_id !== $shop->id) {
            abort(404);
        }

        return view('user.shop.customers.edit', compact('shop', 'customer'));
    }

    public function update(Request $request, ShopCustomer $customer)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $customer->shop_id !== $shop->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:shop_customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()->route('user.shop.customers.show', $customer)
            ->with('success', 'Customer updated successfully!');
    }

    public function adjustLoyaltyPoints(Request $request, ShopCustomer $customer)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $customer->shop_id !== $shop->id) {
            abort(404);
        }

        if (!$shop->hasFeature('has_loyalty')) {
            return back()->with('error', 'Loyalty feature is not available in your current plan.');
        }

        $validated = $request->validate([
            'type' => 'required|in:add,subtract',
            'points' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $points = $validated['type'] === 'add' ? $validated['points'] : -$validated['points'];

        if ($validated['type'] === 'subtract' && $customer->loyalty_points < $validated['points']) {
            return back()->with('error', 'Customer does not have enough points.');
        }

        $newBalance = $customer->loyalty_points + $points;
        
        $customer->loyaltyTransactions()->create([
            'shop_id' => $shop->id,
            'type' => $validated['type'] === 'add' ? 'adjustment_add' : 'adjustment_subtract',
            'points' => $points,
            'balance_after' => $newBalance,
            'description' => $validated['reason'],
        ]);

        $customer->update(['loyalty_points' => $newBalance]);

        return back()->with('success', 'Loyalty points adjusted successfully!');
    }

    public function export(Request $request)
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        $customers = $shop->customers()
            ->withCount('orders')
            ->withSum(['orders' => fn($q) => $q->where('status', 'delivered')], 'total')
            ->get();

        $csv = "Name,Email,Phone,Orders,Total Spent,Loyalty Points,Joined\n";
        
        foreach ($customers as $customer) {
            $csv .= implode(',', [
                '"' . $customer->name . '"',
                $customer->email,
                $customer->phone ?? 'N/A',
                $customer->orders_count,
                $customer->orders_sum_total ?? 0,
                $customer->loyalty_points,
                $customer->created_at->format('Y-m-d'),
            ]) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="customers-' . date('Y-m-d') . '.csv"');
    }

    public function loyalty()
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        if (!($shop->activeSubscription?->plan?->loyalty_enabled ?? false)) {
            return redirect()->route('user.shop.subscription')
                ->with('error', 'Loyalty feature is not available in your current plan.');
        }

        $loyaltySetting = $shop->loyaltySetting;
        
        // Get recent loyalty transactions
        $recentTransactions = \App\Models\ShopLoyaltyTransaction::whereHas('customer', function ($q) use ($shop) {
            $q->where('shop_id', $shop->id);
        })->with('customer')->latest()->limit(20)->get();

        // Stats
        $stats = [
            'total_points_earned' => \App\Models\ShopLoyaltyTransaction::whereHas('customer', fn($q) => $q->where('shop_id', $shop->id))
                ->where('type', 'earned')->sum('points'),
            'total_points_redeemed' => \App\Models\ShopLoyaltyTransaction::whereHas('customer', fn($q) => $q->where('shop_id', $shop->id))
                ->where('type', 'redeemed')->sum('points'),
            'customers_with_points' => $shop->customers()->where('loyalty_points', '>', 0)->count(),
        ];

        return view('user.shop.loyalty.index', compact('shop', 'loyaltySetting', 'recentTransactions', 'stats'));
    }

    public function updateLoyalty(Request $request)
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        $validated = $request->validate([
            'points_per_currency' => 'required|numeric|min:0',
            'points_value' => 'required|numeric|min:0',
            'minimum_points_redemption' => 'required|integer|min:0',
            'maximum_discount_percentage' => 'required|numeric|min:0|max:100',
            'points_expiry_days' => 'nullable|integer|min:0',
            'is_enabled' => 'boolean',
        ]);

        $validated['is_enabled'] = $request->boolean('is_enabled');

        $shop->loyaltySetting()->updateOrCreate(
            ['shop_id' => $shop->id],
            $validated
        );

        return redirect()->route('user.shop.loyalty.index')
            ->with('success', 'Loyalty settings updated successfully!');
    }

    public function loyaltyTransactions(Request $request)
    {
        $shop = $this->getUserShop();

        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        $query = \App\Models\ShopLoyaltyTransaction::whereHas('customer', function ($q) use ($shop) {
            $q->where('shop_id', $shop->id);
        })->with('customer');

        // Filter by type if specified
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by customer if specified
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $transactions = $query->latest()->paginate(50);

        $customers = $shop->customers()->orderBy('name')->get();

        return view('user.shop.loyalty.transactions', compact('shop', 'transactions', 'customers'));
    }
}
