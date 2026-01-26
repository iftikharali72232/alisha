<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopOrderController extends Controller
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

        $query = $shop->orders()->with(['customer', 'items.product']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                    ->orWhereHas('customer', function ($c) use ($request) {
                        $c->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('email', 'like', '%' . $request->search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(15);

        $stats = [
            'total' => $shop->orders()->count(),
            'pending' => $shop->orders()->where('status', 'pending')->count(),
            'processing' => $shop->orders()->where('status', 'processing')->count(),
            'shipped' => $shop->orders()->where('status', 'shipped')->count(),
            'delivered' => $shop->orders()->where('status', 'delivered')->count(),
            'cancelled' => $shop->orders()->where('status', 'cancelled')->count(),
        ];

        return view('user.shop.orders.index', compact('shop', 'orders', 'stats'));
    }

    public function show(ShopOrder $order)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $order->shop_id !== $shop->id) {
            abort(404);
        }

        $order->load(['customer', 'items.product', 'coupon']);

        return view('user.shop.orders.show', compact('shop', 'order'));
    }

    public function updateStatus(Request $request, ShopOrder $order)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $order->shop_id !== $shop->id) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'tracking_number' => 'nullable|string|max:255',
            'shipping_carrier' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $order->status;
        $order->update($validated);

        // Handle inventory and loyalty points based on status change
        if ($validated['status'] === 'delivered' && $oldStatus !== 'delivered') {
            // Award loyalty points
            $order->awardLoyaltyPoints();
        }

        if ($validated['status'] === 'cancelled' && $oldStatus !== 'cancelled') {
            // Restore inventory
            $order->restoreInventory();
            
            // Remove loyalty points if awarded
            if ($oldStatus === 'delivered') {
                $order->removeLoyaltyPoints();
            }
        }

        return back()->with('success', 'Order status updated successfully!');
    }

    public function invoice(ShopOrder $order)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $order->shop_id !== $shop->id) {
            abort(404);
        }

        $order->load(['customer', 'items.product', 'coupon']);

        return view('user.shop.orders.invoice', compact('shop', 'order'));
    }

    public function printInvoice(ShopOrder $order)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $order->shop_id !== $shop->id) {
            abort(404);
        }

        $order->load(['customer', 'items.product', 'coupon']);

        return view('user.shop.orders.print-invoice', compact('shop', 'order'));
    }

    public function export(Request $request)
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        $query = $shop->orders()->with(['customer', 'items.product']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        $csv = "Order Number,Customer,Email,Phone,Status,Items,Subtotal,Discount,Shipping,Tax,Total,Date\n";
        
        foreach ($orders as $order) {
            $items = $order->items->map(fn($i) => $i->quantity . 'x ' . $i->product?->name)->implode('; ');
            $csv .= implode(',', [
                $order->order_number,
                '"' . ($order->customer?->name ?? $order->billing_name) . '"',
                $order->customer?->email ?? $order->billing_email,
                $order->customer?->phone ?? $order->billing_phone,
                $order->status,
                '"' . $items . '"',
                $order->subtotal,
                $order->discount,
                $order->shipping_cost,
                $order->tax,
                $order->total,
                $order->created_at->format('Y-m-d H:i:s'),
            ]) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="orders-' . date('Y-m-d') . '.csv"');
    }
}
