<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\ShopProductVariant;
use App\Models\ShopCategory;
use App\Models\ShopCoupon;
use App\Models\ShopCustomer;
use App\Models\ShopOrder;
use App\Models\ShopOrderItem;
use App\Models\ShopOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PublicShopController extends Controller
{
    /**
     * All shops listing page
     */
    public function allShops(Request $request)
    {
        $query = Shop::active()
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->with('categories');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $shops = $query->latest()->paginate(12);

        return view('shops.index', compact('shops'));
    }

    /**
     * Individual shop storefront
     */
    public function show(Shop $shop)
    {
        if ($shop->status !== 'active') {
            abort(404);
        }

        $shop->load([
            'sliders' => fn($q) => $q->where('is_active', true)->orderBy('order'),
            'categories' => fn($q) => $q->where('is_active', true)->withCount('products'),
            'galleries' => fn($q) => $q->where('is_active', true)->orderBy('order')->take(8),
        ]);

        $featuredProducts = $shop->products()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->with(['category'])
            ->take(8)
            ->get();

        $newArrivals = $shop->products()
            ->where('is_active', true)
            ->with(['category'])
            ->latest()
            ->take(8)
            ->get();

        $bestSellers = $shop->products()
            ->where('is_active', true)
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->with(['category'])
            ->take(8)
            ->get();

        $activeOffers = $shop->offers()
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->get();

        $reviews = $shop->products()
            ->with(['reviews' => fn($q) => $q->where('status', 'approved')->with('customer')->latest()->take(3)])
            ->get()
            ->pluck('reviews')
            ->flatten()
            ->take(6);

        return view('shops.show', compact('shop', 'featuredProducts', 'newArrivals', 'bestSellers', 'activeOffers', 'reviews'));
    }

    /**
     * Shop products listing
     */
    public function products(Request $request, Shop $shop)
    {
        if ($shop->status !== 'active') {
            abort(404);
        }

        $shop->load([
            'categories' => fn($q) => $q->where('is_active', true)->withCount('products'),
            'activeSubscription.plan',
        ]);

        $query = $shop->products()
            ->where('is_active', true)
            ->with(['category', 'brand']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        $query = match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'name' => $query->orderBy('name', 'asc'),
            'popular' => $query->withCount('orderItems')->orderByDesc('order_items_count'),
            default => $query->latest(),
        };

        $products = $query->paginate(12);
        $categories = $shop->categories()->where('is_active', true)->orderBy('name')->get();
        $brands = $shop->brands()->where('is_active', true)->orderBy('name')->get();

        return view('shops.products', compact('shop', 'products', 'categories', 'brands'));
    }

    /**
     * Single product page
     */
    public function product(Shop $shop, ShopProduct $product)
    {
        // Check shop is active and product belongs to this shop
        if ($shop->status !== 'active' || (int) $product->shop_id !== (int) $shop->id || !$product->is_active) {
            abort(404);
        }

        $shop->load([
            'categories' => fn($q) => $q->where('is_active', true)->withCount('products'),
        ]);

        $product->load(['category', 'brand', 'variants', 'reviews' => fn($q) => $q->where('status', 'approved')->with('customer')->latest()]);

        $relatedProducts = $shop->products()
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->take(4)
            ->get();

        // Increment view count
        $product->incrementViews();

        return view('shops.product', compact('shop', 'product', 'relatedProducts'));
    }

    /**
     * Category products
     */
    public function category(Shop $shop, ShopCategory $category)
    {
        // Check shop is active and category belongs to this shop
        if ($shop->status !== 'active' || (int) $category->shop_id !== (int) $shop->id || !$category->is_active) {
            abort(404);
        }

        $shop->load([
            'categories' => fn($q) => $q->where('is_active', true)->withCount('products'),
        ]);

        $products = $shop->products()
            ->where('is_active', true)
            ->where('category_id', $category->id)
            ->paginate(12);

        return view('shops.category', compact('shop', 'category', 'products'));
    }

    /**
     * Apply coupon code
     */
    public function applyCoupon(Request $request, Shop $shop)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $coupon = $shop->coupons()
            ->where('code', strtoupper($request->coupon_code))
            ->first();

        if (!$coupon) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid coupon code.',
                ], 422);
            }
            return back()->with('coupon_error_' . $shop->id, 'Invalid coupon code.');
        }

        // Calculate current cart subtotal
        $cartItems = session('cart_' . $shop->id, []);
        $subtotal = 0;
        foreach ($cartItems as $cartKey => $item) {
            $parts = explode('_', $cartKey);
            $productId = $parts[0];
            $product = ShopProduct::find($productId);
            if ($product && $product->is_active && (int) $product->shop_id === (int) $shop->id) {
                $price = $product->getFinalPrice();
                if (isset($parts[1])) {
                    $variant = $product->variants()->find($parts[1]);
                    if ($variant) {
                        $price = $variant->price ?? $price;
                    }
                }
                $subtotal += $price * $item['quantity'];
            }
        }

        // Check if coupon is valid
        $customerId = session('shop_customer_' . $shop->id);
        $errorMessage = $coupon->getValidationError($subtotal, $customerId);

        if ($errorMessage) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                ], 422);
            }
            return back()->with('coupon_error_' . $shop->id, $errorMessage);
        }

        $discount = $coupon->calculateDiscount($subtotal);

        // Store coupon in session
        session(['cart_coupon_' . $shop->id => $coupon->code]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'coupon' => [
                    'code' => $coupon->code,
                    'name' => $coupon->name,
                    'type' => $coupon->type,
                    'discount' => $discount,
                ],
            ]);
        }

        return back()->with('success', 'Coupon applied successfully!');
    }

    /**
     * Cart page
     */
    public function cart(Shop $shop)
    {
        if ($shop->status !== 'active') {
            abort(404);
        }

        $shop->load([
            'categories' => fn($q) => $q->where('is_active', true)->withCount('products'),
        ]);

        $cartItems = session('cart_' . $shop->id, []);
        // Ensure cart items belong to this shop (defensive)
        $cartItems = array_filter($cartItems, function($item, $key) use ($shop) {
            $parts = explode('_', $key);
            $product = \App\Models\ShopProduct::find($parts[0]);
            return $product && $product->is_active && (int) $product->shop_id === (int) $shop->id;
        }, ARRAY_FILTER_USE_BOTH);

        $cart = [];
        $subtotal = 0;

        foreach ($cartItems as $cartKey => $item) {
            // Parse cart key: product_id or product_id_variant_id
            $parts = explode('_', $cartKey);
            $productId = $parts[0];
            $variantId = isset($parts[1]) ? $parts[1] : null;

            $product = ShopProduct::find($productId);
            if ($product && $product->is_active) {
                $price = $product->getFinalPrice();
                $variant = null;
                
                if ($variantId) {
                    $variant = $product->variants()->find($variantId);
                    if ($variant) {
                        $price = $variant->price ?? $price;
                    }
                }

                $cart[$cartKey] = [
                    'name' => $product->name,
                    'image' => $variant ? $variant->image : $product->featured_image,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'variant' => $variant ? $variant->name : null,
                    'total' => $price * $item['quantity'],
                ];
                $subtotal += $price * $item['quantity'];
            }
        }

        // Calculate coupon discount
        $discount = 0;
        $appliedCoupon = null;
        $couponCode = session('cart_coupon_' . $shop->id);
        if ($couponCode) {
            $coupon = $shop->coupons()->where('code', strtoupper($couponCode))->first();
            if ($coupon) {
                $customerId = session('shop_customer_' . $shop->id);
                if (!$coupon->getValidationError($subtotal, $customerId)) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $appliedCoupon = $coupon;
                } else {
                    // Coupon is no longer valid, remove it
                    session()->forget('cart_coupon_' . $shop->id);
                }
            } else {
                // Coupon doesn't exist, remove it
                session()->forget('cart_coupon_' . $shop->id);
            }
        }

        return view('shops.cart', compact('shop', 'cart', 'subtotal', 'discount', 'appliedCoupon'));
    }

    /**
     * Add to cart
     */
    public function addToCart(Request $request, Shop $shop, ShopProduct $product)
    {
        // Verify shop is active and product belongs to this shop
        if ($shop->status !== 'active' || !$product->is_active || (int) $product->shop_id !== (int) $shop->id) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Product not available'], 404);
            }
            return back()->with('error', 'Product not available');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:shop_product_variants,id',
        ]);

        $cart = session('cart_' . $shop->id, []);
        $cartKey = $product->id . ($request->variant_id ? '_' . $request->variant_id : '');

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'variant_id' => $request->variant_id,
            ];
        }

        session(['cart_' . $shop->id => $cart]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'cart_count' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return back()->with('success', 'Product added to cart!');
    }

    /**
     * Update cart
     */
    public function updateCart(Request $request, Shop $shop)
    {
        $cart = session('cart_' . $shop->id, []);

        if ($request->has('product_id') && $request->has('action')) {
            // Handle single item update (increase/decrease)
            $productId = $request->product_id;
            $action = $request->action;

            if (isset($cart[$productId])) {
                if ($action === 'increase') {
                    $cart[$productId]['quantity']++;
                } elseif ($action === 'decrease') {
                    $cart[$productId]['quantity']--;
                    if ($cart[$productId]['quantity'] <= 0) {
                        unset($cart[$productId]);
                    }
                }
            }
        } elseif ($request->has('items')) {
            // Handle bulk update
            $request->validate([
                'items' => 'required|array',
                'items.*.key' => 'required|string',
                'items.*.quantity' => 'required|integer|min:0',
            ]);

            foreach ($request->items as $item) {
                if ($item['quantity'] > 0) {
                    if (isset($cart[$item['key']])) {
                        $cart[$item['key']]['quantity'] = $item['quantity'];
                    }
                } else {
                    unset($cart[$item['key']]);
                }
            }
        }

        session(['cart_' . $shop->id => $cart]);

        return back()->with('success', 'Cart updated!');
    }

    /**
     * Remove from cart
     */
    public function removeFromCart(Request $request, Shop $shop)
    {
        $request->validate([
            'product_id' => 'required|string',
        ]);

        $cart = session('cart_' . $shop->id, []);
        unset($cart[$request->product_id]);
        session(['cart_' . $shop->id => $cart]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart!',
                'cart_count' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return back()->with('success', 'Item removed from cart!');
    }

    /**
     * Checkout page
     */
    public function checkout(Shop $shop)
    {
        if ($shop->status !== 'active') {
            abort(404);
        }

        $shop->load([
            'categories' => fn($q) => $q->where('is_active', true)->withCount('products'),
        ]);

        $cartItems = session('cart_' . $shop->id, []);
        // Defensive filter: remove items referencing products not belonging to this shop
        $cartItems = array_filter($cartItems, function($item, $key) use ($shop) {
            $parts = explode('_', $key);
            $product = \App\Models\ShopProduct::find($parts[0]);
            return $product && $product->is_active && (int) $product->shop_id === (int) $shop->id;
        }, ARRAY_FILTER_USE_BOTH);
        
        if (empty($cartItems)) {
            return redirect()->route('shop.cart', $shop->slug)
                ->with('error', 'Your cart is empty.');
        }

        $cart = [];
        $subtotal = 0;

        foreach ($cartItems as $cartKey => $item) {
            // Parse cart key: product_id or product_id_variant_id
            $parts = explode('_', $cartKey);
            $productId = $parts[0];
            $variantId = isset($parts[1]) ? $parts[1] : null;

            $product = ShopProduct::find($productId);
            if ($product && $product->is_active) {
                $price = $product->getFinalPrice();
                $variant = null;
                
                if ($variantId) {
                    $variant = $product->variants()->find($variantId);
                    if ($variant) {
                        $price = $variant->price ?? $price;
                    }
                }

                $cart[$cartKey] = [
                    'name' => $product->name,
                    'image' => $variant ? $variant->image : $product->featured_image,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'variant' => $variant ? $variant->name : null,
                    'total' => $price * $item['quantity'],
                ];
                $subtotal += $price * $item['quantity'];
            }
        }

        // Calculate totals (discount will be applied during order placement)
        $discount = 0; // Don't pre-calculate discount
        $appliedCoupon = null;
        $couponCode = session('cart_coupon_' . $shop->id);
        if ($couponCode) {
            $coupon = $shop->coupons()->where('code', strtoupper($couponCode))->first();
            if ($coupon) {
                $customerId = session('shop_customer_' . $shop->id);
                if (!$coupon->getValidationError($subtotal, $customerId)) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $appliedCoupon = $coupon;
                }
            }
        }
        $tax = 0; // Calculate based on products
        $shipping = 0; // Can be configured per shop - free shipping for now
        $total = $subtotal - $discount + $tax + $shipping;

        // Get customer if logged in
        $customerId = session('shop_customer_' . $shop->id);
        $customer = $customerId ? ShopCustomer::find($customerId) : null;

        // States/Provinces of Pakistan
        $states = [
            'Punjab',
            'Sindh',
            'Khyber Pakhtunkhwa',
            'Balochistan',
            'Azad Jammu and Kashmir',
            'Gilgit-Baltistan',
            'Islamabad Capital Territory'
        ];

        // Comprehensive list of Pakistani cities
        $cities = [
            // Punjab Province
            'Lahore', 'Faisalabad', 'Rawalpindi', 'Multan', 'Gujranwala', 'Bahawalpur', 'Sargodha',
            'Sialkot', 'Sheikhupura', 'Jhang', 'Gujrat', 'Kasur', 'Rahim Yar Khan', 'Sahiwal',
            'Okara', 'Pakpattan', 'Chiniot', 'Hafizabad', 'Khanewal', 'Mandi Bahauddin', 'Daska',
            'Gojra', 'Muridke', 'Bahawalnagar', 'Samundri', 'Jaranwala', 'Chishtian', 'Ahmadpur East',
            'Kamoke', 'Kharian', 'Mianwali', 'Jhelum', 'Attock', 'Vehari', 'Wah Cantonment',
            'Arifwala', 'Burewala', 'Chakwal', 'Dera Ghazi Khan', 'Ferozewala', 'Hujra Shah Muqeem',
            'Kabirwala', 'Khanpur', 'Kot Addu', 'Mian Channu', 'Mianwali', 'Muzaffargarh',
            'Narowal', 'Nankana Sahib', 'Pattoki', 'Phalia', 'Pindi Bhattian', 'Qila Didar Singh',
            'Raiwind', 'Renala Khurd', 'Shakargarh', 'Shujaabad', 'Sillanwali', 'Toba Tek Singh',
            'Vihari', 'Yazman', 'Zafarwal',

            // Sindh Province
            'Karachi', 'Hyderabad', 'Sukkur', 'Larkana', 'Nawabshah', 'Mirpur Khas', 'Jacobabad',
            'Shikarpur', 'Dadu', 'Tando Allahyar', 'Tando Muhammad Khan', 'Badin', 'Thatta',
            'Khairpur', 'Sanghar', 'Umerkot', 'Ghotki', 'Naushahro Feroze', 'Shahdadkot',
            'Kambar', 'Qambar', 'Warah', 'Nasirabad', 'Kandhkot', 'Hala', 'Kunri', 'Mehar',
            'Matiari', 'Moro', 'Nagarparkar', 'Rohri', 'Sakrand', 'Sehwan', 'Shahpur Chakar',
            'Sinjhoro', 'Sujawal', 'Tangi', 'Thul', 'Ubauro',

            // Khyber Pakhtunkhwa Province
            'Peshawar', 'Mardan', 'Mingora', 'Kohat', 'Abbottabad', 'Mansehra', 'Swabi', 'Nowshera',
            'Charsadda', 'Dera Ismail Khan', 'Peshawar', 'Bannu', 'Timergara', 'Hangu', 'Karak',
            'Kurram', 'Lakki Marwat', 'North Waziristan', 'South Waziristan', 'Tank', 'Bajaur',
            'Mohmand', 'Orakzai', 'Khyber', 'Buner', 'Shangla', 'Swat', 'Upper Dir', 'Lower Dir',
            'Malakand', 'Tor Ghar', 'Batagram', 'Kolai-Palas', 'Alpuri', 'Haripur', 'Havelian',

            // Balochistan Province
            'Quetta', 'Turbat', 'Khuzdar', 'Chaman', 'Gwadar', 'Sibi', 'Loralai', 'Zhob', 'Pishin',
            'Killa Abdullah', 'Nushki', 'Chagai', 'Kalat', 'Mastung', 'Kharan', 'Washuk', 'Awaran',
            'Kech', 'Panigur', 'Uthal', 'Lasbela', 'Jafarabad', 'Nasirabad', 'Jhal Magsi', 'Kachhi',
            'Sohbatpur', 'Dera Bugti', 'Kohlu', 'Barkhan', 'Musakhel', 'Sherani', 'Harnai', 'Ziarat',

            // Azad Jammu and Kashmir
            'Muzaffarabad', 'Mirpur', 'Kotli', 'Bhimbher', 'Rawalakot', 'Bagh', 'Hattian Bala',
            'Haveli', 'Forward Kahuta', 'Sudhanoti', 'Poonch', 'Neelum', 'Hajira',

            // Gilgit-Baltistan
            'Gilgit', 'Skardu', 'Chilas', 'Astore', 'Diamer', 'Ghizer', 'Ghanche', 'Kharmang',
            'Shigar', 'Hunza', 'Nagar', 'Gupis-Yasin', 'Rondu', 'Darel', 'Tangir', 'Dassu',

            // Islamabad Capital Territory
            'Islamabad'
        ];

        return view('shops.checkout', compact('shop', 'cart', 'subtotal', 'discount', 'shipping', 'tax', 'total', 'appliedCoupon', 'customer', 'cities', 'states'));
    }

    /**
     * Place order
     */
    public function placeOrder(Request $request, Shop $shop)
    {
        if ($shop->status !== 'active') {
            abort(404);
        }

        $cartItems = session('cart_' . $shop->id, []);
        // Defensive filter to ensure items belong to this shop
        $cartItems = array_filter($cartItems, function ($item, $key) use ($shop) {
            $productId = $item['product_id'] ?? explode('_', $key)[0];
            $product = \App\Models\ShopProduct::find($productId);
            return $product && $product->is_active && (int) $product->shop_id === (int) $shop->id;
        }, ARRAY_FILTER_USE_BOTH);
        
        if (empty($cartItems)) {
            return redirect()->route('shop.cart', $shop->slug)
                ->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_phone' => 'required|string|max:20',
            'billing_address' => 'required|string',
            'billing_city' => 'required|string|max:100',
            'billing_state' => 'required|string|max:100',
            'billing_country' => 'required|string|max:100',
            'shipping_same' => 'boolean',
            'shipping_name' => 'required_if:shipping_same,false|nullable|string|max:255',
            'shipping_phone' => 'required_if:shipping_same,false|nullable|string|max:20',
            'shipping_address' => 'required_if:shipping_same,false|nullable|string',
            'shipping_city' => 'required_if:shipping_same,false|nullable|string|max:100',
            'shipping_state' => 'required_if:shipping_same,false|nullable|string|max:100',
            'shipping_country' => 'required_if:shipping_same,false|nullable|string|max:100',
            'coupon_code' => 'nullable|string',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:cod,bank_transfer,online',
            'use_loyalty_points' => 'boolean',
        ]);

        // Calculate totals
        $subtotal = 0;
        $orderItems = [];

        foreach ($cartItems as $key => $item) {
            $product = ShopProduct::find($item['product_id']);
            if (!$product || !$product->is_active) {
                continue;
            }

            $price = $product->getFinalPrice();
            $subtotal += $price * $item['quantity'];

            $variantName = null;
            if ($item['variant_id']) {
                $variant = ShopProductVariant::find($item['variant_id']);
                $variantName = $variant ? $variant->name : null;
            }

            $orderItems[] = [
                'product_id' => $product->id,
                'variant_id' => $item['variant_id'] ?? null,
                'product_name' => $product->name,
                'variant_name' => $variantName,
                'sku' => $product->sku,
                'price' => $price,
                'quantity' => $item['quantity'],
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total' => $price * $item['quantity'],
            ];

            // Reduce inventory
            $product->decrement('quantity', $item['quantity']);
        }

        // Apply coupon
        $discount = 0;
        $couponId = null;
        $couponCode = $validated['coupon_code'] ?? null;
        if ($couponCode) {
            $coupon = $shop->coupons()->where('code', strtoupper($couponCode))->first();
            if ($coupon && !$coupon->getValidationError($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
                $couponId = $coupon->id;
            }
        }

        // Get or create customer
        $customerId = session('shop_customer_' . $shop->id);
        $customer = null;
        
        if (!$customerId) {
            // Create guest customer
            $customer = ShopCustomer::create([
                'shop_id' => $shop->id,
                'name' => $validated['billing_name'],
                'email' => $validated['billing_email'],
                'phone' => $validated['billing_phone'],
            ]);
            $customerId = $customer->id;
        } else {
            $customer = ShopCustomer::find($customerId);
        }

        // Apply loyalty points
        $loyaltyDiscount = 0;
        if ($request->boolean('use_loyalty_points') && $customer && $customer->loyalty_points > 0) {
            $loyaltySetting = $shop->loyaltySetting;
            if ($loyaltySetting && $loyaltySetting->is_enabled) {
                $maxDiscount = $loyaltySetting->getMaximumDiscount($subtotal - $discount);
                $pointsValue = $loyaltySetting->calculatePointsValue($customer->loyalty_points);
                $loyaltyDiscount = min($pointsValue, $maxDiscount);
            }
        }

        // Calculate tax and shipping
        $taxRate = 0; // Can be configured per shop
        $tax = ($subtotal - $discount - $loyaltyDiscount) * ($taxRate / 100);
        $shippingCost = 0; // Can be configured per shop
        $total = $subtotal - $discount - $loyaltyDiscount + $tax + $shippingCost;

        // Shipping address
        $shippingSame = $request->boolean('shipping_same', true);

        // Create order
        $order = ShopOrder::create([
            'shop_id' => $shop->id,
            'customer_id' => $customerId,
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $validated['payment_method'],
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'discount_amount' => $discount + $loyaltyDiscount,
            'shipping_amount' => $shippingCost,
            'total' => $total,
            'coupon_code' => $couponCode,
            'loyalty_points_used' => $loyaltyDiscount > 0 ? $customer->loyalty_points : 0,
            'loyalty_discount' => $loyaltyDiscount,
            'billing_name' => $validated['billing_name'],
            'billing_email' => $validated['billing_email'],
            'billing_phone' => $validated['billing_phone'],
            'billing_address' => $validated['billing_address'],
            'billing_city' => $validated['billing_city'],
            'billing_state' => $validated['billing_state'] ?? null,
            'billing_postal_code' => $validated['billing_zip'] ?? null,
            'billing_country' => $validated['billing_country'],
            'shipping_name' => $shippingSame ? $validated['billing_name'] : $validated['shipping_name'],
            'shipping_phone' => $shippingSame ? $validated['billing_phone'] : $validated['shipping_phone'],
            'shipping_address' => $shippingSame ? $validated['billing_address'] : $validated['shipping_address'],
            'shipping_city' => $shippingSame ? $validated['billing_city'] : $validated['shipping_city'],
            'shipping_state' => $shippingSame ? ($validated['billing_state'] ?? null) : ($validated['shipping_state'] ?? null),
            'shipping_postal_code' => $shippingSame ? ($validated['billing_zip'] ?? null) : ($validated['shipping_zip'] ?? null),
            'shipping_country' => $shippingSame ? $validated['billing_country'] : $validated['shipping_country'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Create order items
        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        // Record coupon usage
        if ($couponId) {
            $coupon->recordUsage($customerId, $order->id, $discount);
        }

        // Deduct loyalty points
        if ($loyaltyDiscount > 0 && $customer) {
            $customer->loyaltyTransactions()->create([
                'shop_id' => $shop->id,
                'order_id' => $order->id,
                'type' => 'redeemed',
                'points' => -$customer->loyalty_points,
                'balance_after' => 0,
                'description' => 'Redeemed for order ' . $order->order_number,
            ]);
            $customer->update(['loyalty_points' => 0]);
        }

        // Clear cart
        session()->forget('cart_' . $shop->id);

        return redirect()->route('shop.order.confirmation', [$shop->slug, $order->order_number])
            ->with('success', 'Order placed successfully!');
    }

    /**
     * Order confirmation page
     */
    public function orderConfirmation(Shop $shop, string $orderNumber)
    {
        $order = ShopOrder::where('shop_id', $shop->id)
            ->where('order_number', $orderNumber)
            ->with(['items.product', 'customer'])
            ->firstOrFail();

        return view('shops.order-confirmation', compact('shop', 'order'));
    }

    /**
     * Customer login page
     */
    public function customerLogin(Shop $shop)
    {
        if ($shop->status !== 'active') {
            abort(404);
        }

        $shop->load([
            'categories' => fn($q) => $q->where('is_active', true)->withCount('products'),
        ]);

        $customerId = session('shop_customer_' . $shop->id);
        if ($customerId) {
            return redirect()->route('shop.account', $shop->slug);
        }

        return view('shops.login', compact('shop'));
    }

    /**
     * Customer login submit
     */
    public function customerLoginSubmit(Request $request, Shop $shop)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $customer = ShopCustomer::where('shop_id', $shop->id)
            ->where('email', $request->email)
            ->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return back()->with('error', 'Invalid credentials.');
        }

        session(['shop_customer_' . $shop->id => $customer->id]);

        return redirect()->route('shop.account', $shop->slug)
            ->with('success', 'Welcome back, ' . $customer->name . '!');
    }

    /**
     * Customer register
     */
    public function customerRegister(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:shop_customers,email,NULL,id,shop_id,' . $shop->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $customer = ShopCustomer::create([
            'shop_id' => $shop->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
        ]);

        // Award signup bonus points
        $loyaltySetting = $shop->loyaltySetting;
        if ($loyaltySetting && $loyaltySetting->is_enabled && $loyaltySetting->signup_bonus_points > 0) {
            $customer->loyaltyTransactions()->create([
                'shop_id' => $shop->id,
                'type' => 'signup_bonus',
                'points' => $loyaltySetting->signup_bonus_points,
                'balance_after' => $loyaltySetting->signup_bonus_points,
                'description' => 'Signup bonus',
                'expires_at' => $loyaltySetting->points_expiry_days 
                    ? now()->addDays($loyaltySetting->points_expiry_days) 
                    : null,
            ]);
            $customer->update(['loyalty_points' => $loyaltySetting->signup_bonus_points]);
        }

        session(['shop_customer_' . $shop->id => $customer->id]);

        return redirect()->route('shop.account', $shop->slug)
            ->with('success', 'Account created successfully!');
    }

    /**
     * Customer account
     */
    public function customerAccount(Shop $shop)
    {
        $customerId = session('shop_customer_' . $shop->id);
        
        if (!$customerId) {
            return redirect()->route('shop.login', $shop->slug);
        }

        $shop->load([
            'categories' => fn($q) => $q->where('is_active', true)->withCount('products'),
            'loyaltySettings',
        ]);

        $customer = ShopCustomer::with([
            'orders' => fn($q) => $q->latest()->take(5),
            'addresses',
        ])->find($customerId);

        if (!$customer) {
            session()->forget('shop_customer_' . $shop->id);
            return redirect()->route('shop.login', $shop->slug);
        }

        // Calculate customer stats
        $stats = [
            'total_orders' => $customer->orders()->count(),
            'total_spent' => $customer->orders()->sum('total'),
        ];

        return view('shops.account', compact('shop', 'customer', 'stats'));
    }

    /**
     * Customer logout
     */
    public function customerLogout(Shop $shop)
    {
        session()->forget('shop_customer_' . $shop->id);
        
        return redirect()->route('shop.show', $shop->slug)
            ->with('success', 'You have been logged out.');
    }

    /**
     * Customer orders list
     */
    public function customerOrders(Shop $shop)
    {
        $customerId = session('shop_customer_' . $shop->id);
        
        if (!$customerId) {
            return redirect()->route('shop.login', $shop->slug);
        }

        $customer = ShopCustomer::find($customerId);
        $orders = $customer->orders()->with('items.product')->latest()->paginate(10);

        return view('shops.orders', compact('shop', 'customer', 'orders'));
    }

    /**
     * Categories list
     */
    public function categories(Shop $shop)
    {
        $shop->load([
            'categories' => fn($q) => $q->where('is_active', true)->withCount('products'),
        ]);

        $categories = $shop->categories()
            ->where('is_active', true)
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->orderBy('name')
            ->get();

        return view('shops.categories', compact('shop', 'categories'));
    }

    /**
     * Search products
     */
    public function search(Request $request, Shop $shop)
    {
        $shop->load([
            'categories' => fn($q) => $q->where('is_active', true)->withCount('products'),
        ]);

        $query = $request->get('q');
        
        $products = $shop->products()
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%')
                    ->orWhere('sku', 'like', '%' . $query . '%');
            })
            ->paginate(20);

        return view('shops.search', compact('shop', 'products', 'query'));
    }

    /**
     * Offers list
     */
    public function offers(Shop $shop)
    {
        $offers = $shop->offers()
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->with('product')
            ->get();

        return view('shops.offers', compact('shop', 'offers'));
    }

    /**
     * Single offer page
     */
    public function offer(Shop $shop, ShopOffer $offer)
    {
        if ((int) $offer->shop_id !== (int) $shop->id) {
            abort(404);
        }

        $products = $offer->product 
            ? collect([$offer->product])
            : $shop->products()->where('is_active', true)->inRandomOrder()->limit(12)->get();

        return view('shops.offer', compact('shop', 'offer', 'products'));
    }

    /**
     * Contact page
     */
    public function contact(Shop $shop)
    {
        return view('shops.contact', compact('shop'));
    }

    /**
     * Submit product review
     */
    public function submitReview(Request $request, Shop $shop, ShopProduct $product)
    {
        if (!$shop->hasFeature('has_reviews')) {
            return back()->with('error', 'Reviews are not enabled for this shop.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'review' => 'required|string|min:10',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $customerId = session('shop_customer_' . $shop->id);

        $product->reviews()->create([
            'customer_id' => $customerId,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'review' => $validated['review'],
            'status' => 'pending',
            'is_verified_purchase' => $customerId ? $product->orderItems()
                ->whereHas('order', fn($q) => $q->where('customer_id', $customerId)->where('status', 'delivered'))
                ->exists() : false,
        ]);

        return back()->with('success', 'Thank you for your review! It will be published after approval.');
    }
}
