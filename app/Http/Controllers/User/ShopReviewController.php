<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopReviewController extends Controller
{
    private function getUserShop()
    {
        return Shop::where('user_id', Auth::id())->first();
    }

    public function index(Request $request)
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        $query = ShopProductReview::whereHas('product', function ($q) use ($shop) {
            $q->where('shop_id', $shop->id);
        })->with(['product', 'customer']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('comment', 'like', '%' . $search . '%')
                    ->orWhereHas('product', function ($pq) use ($search) {
                        $pq->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Rating filter
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->latest()->paginate(15);

        // Stats
        $stats = [
            'total' => ShopProductReview::whereHas('product', fn($q) => $q->where('shop_id', $shop->id))->count(),
            'average' => ShopProductReview::whereHas('product', fn($q) => $q->where('shop_id', $shop->id))->avg('rating') ?? 0,
            'approved' => ShopProductReview::whereHas('product', fn($q) => $q->where('shop_id', $shop->id))->where('status', 'approved')->count(),
            'pending' => ShopProductReview::whereHas('product', fn($q) => $q->where('shop_id', $shop->id))->where('status', 'pending')->count(),
        ];

        return view('user.shop.reviews.index', compact('shop', 'reviews', 'stats'));
    }

    public function approve(ShopProductReview $review)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $review->product->shop_id !== $shop->id) {
            abort(404);
        }

        $review->update(['status' => 'approved']);

        return back()->with('success', 'Review approved successfully!');
    }

    public function reject(ShopProductReview $review)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $review->product->shop_id !== $shop->id) {
            abort(404);
        }

        $review->update(['status' => 'rejected']);

        return back()->with('success', 'Review rejected.');
    }

    public function reply(Request $request, ShopProductReview $review)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $review->product->shop_id !== $shop->id) {
            abort(404);
        }

        $validated = $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $review->update([
            'reply' => $validated['reply'],
            'replied_at' => now(),
        ]);

        return back()->with('success', 'Reply sent successfully!');
    }

    public function destroy(ShopProductReview $review)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $review->product->shop_id !== $shop->id) {
            abort(404);
        }

        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
