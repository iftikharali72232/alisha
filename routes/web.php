<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ShopController as AdminShopController;
use App\Http\Controllers\Admin\ShopSubscriptionPlanController;
use App\Http\Controllers\Admin\DeveloperController;
use App\Http\Controllers\User\ShopDashboardController;
use App\Http\Controllers\User\ShopProductController;
use App\Http\Controllers\User\ShopOrderController;
use App\Http\Controllers\User\ShopCategoryController;
use App\Http\Controllers\User\ShopOfferController;
use App\Http\Controllers\User\ShopCouponController;
use App\Http\Controllers\User\ShopCustomerController;
use App\Http\Controllers\User\ShopSliderController;
use App\Http\Controllers\User\ShopReviewController;
use App\Http\Controllers\User\ShopSettingsController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PublicShopController;

Route::get('/', [BlogController::class, 'index'])->name('home');

// Blog Frontend Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{slug}/comment', [BlogController::class, 'storeComment'])->name('blog.comment');
Route::post('/blog/{slug}/reply', [BlogController::class, 'storeReply'])->name('blog.reply');
Route::get('/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/gallery', [BlogController::class, 'gallery'])->name('blog.gallery');
Route::get('/page/{slug}', [BlogController::class, 'page'])->name('blog.page');
Route::get('/about', [BlogController::class, 'about'])->name('blog.about');
Route::get('/contact', [BlogController::class, 'contact'])->name('blog.contact');
Route::post('/contact', [BlogController::class, 'sendContact'])->name('blog.contact.send');

// Auth Routes
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user', [UserDashboardController::class, 'index'])->name('user.dashboard');
    
    // User Profile and Settings Routes
    Route::get('/user/profile', [UserDashboardController::class, 'profile'])->name('user.profile');
    Route::put('/user/profile', [UserDashboardController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/user/settings', [UserDashboardController::class, 'settings'])->name('user.settings');
    Route::put('/user/settings', [UserDashboardController::class, 'updateSettings'])->name('user.settings.update');
    Route::put('/user/password', [UserDashboardController::class, 'updatePassword'])->name('user.password.update');

    // TinyMCE image upload (User)
    Route::post('/user/upload-image', [UserDashboardController::class, 'uploadImage'])->name('user.upload-image');
    
    // User Post Management Routes
    Route::get('/user/posts', [UserDashboardController::class, 'posts'])->name('user.posts.index');
    Route::get('/user/posts/create', [UserDashboardController::class, 'createPost'])->name('user.posts.create');
    Route::post('/user/posts', [UserDashboardController::class, 'storePost'])->name('user.posts.store');
    Route::get('/user/posts/{post}', [UserDashboardController::class, 'showPost'])->name('user.posts.show');
    Route::get('/user/posts/{post}/edit', [UserDashboardController::class, 'editPost'])->name('user.posts.edit');
    Route::put('/user/posts/{post}', [UserDashboardController::class, 'updatePost'])->name('user.posts.update');
    Route::delete('/user/posts/{post}', [UserDashboardController::class, 'destroyPost'])->name('user.posts.destroy');
    Route::patch('/user/posts/{post}/toggle-status', [UserDashboardController::class, 'togglePostStatus'])->name('user.posts.toggle-status');
    
    // User Shop Management Routes
    Route::prefix('user/shop')->name('user.shop.')->group(function () {
        Route::get('/create', [ShopDashboardController::class, 'create'])->name('create');
        Route::post('/store', [ShopDashboardController::class, 'store'])->name('store');
        Route::get('/dashboard', [ShopDashboardController::class, 'index'])->name('dashboard');
        Route::get('/subscription', [ShopDashboardController::class, 'subscription'])->name('subscription');
        
        // Products
        Route::resource('products', ShopProductController::class)->names('products');
        Route::post('products/{product}/toggle-status', [ShopProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::delete('products/{product}/image', [ShopProductController::class, 'deleteImage'])->name('products.delete-image');
        
        // Categories
        Route::resource('categories', ShopCategoryController::class)->names('categories');
        Route::post('categories/{category}/toggle-status', [ShopCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
        
        // Orders
        Route::get('orders', [ShopOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/export', [ShopOrderController::class, 'export'])->name('orders.export');
        Route::get('orders/{order}', [ShopOrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{order}/status', [ShopOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('orders/{order}/invoice', [ShopOrderController::class, 'invoice'])->name('orders.invoice');
        Route::get('orders/{order}/print', [ShopOrderController::class, 'printInvoice'])->name('orders.print');
        
        // Offers
        Route::resource('offers', ShopOfferController::class)->names('offers');
        Route::post('offers/{offer}/toggle-status', [ShopOfferController::class, 'toggleStatus'])->name('offers.toggle-status');
        
        // Coupons
        Route::resource('coupons', ShopCouponController::class)->names('coupons');
        Route::post('coupons/{coupon}/toggle-status', [ShopCouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
        Route::get('coupons/generate-code', [ShopCouponController::class, 'generateCode'])->name('coupons.generate-code');
        
        // Customers
        Route::get('customers', [ShopCustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/export', [ShopCustomerController::class, 'export'])->name('customers.export');
        Route::get('customers/{customer}', [ShopCustomerController::class, 'show'])->name('customers.show');
        Route::get('customers/{customer}/edit', [ShopCustomerController::class, 'edit'])->name('customers.edit');
        Route::put('customers/{customer}', [ShopCustomerController::class, 'update'])->name('customers.update');
        Route::post('customers/{customer}/loyalty', [ShopCustomerController::class, 'adjustLoyaltyPoints'])->name('customers.loyalty');
        
        // Sliders
        Route::get('sliders', [ShopSliderController::class, 'index'])->name('sliders.index');
        Route::post('sliders', [ShopSliderController::class, 'store'])->name('sliders.store');
        Route::put('sliders/{slider}', [ShopSliderController::class, 'update'])->name('sliders.update');
        Route::delete('sliders/{slider}', [ShopSliderController::class, 'destroy'])->name('sliders.destroy');
        
        // Reviews
        Route::get('reviews', [ShopReviewController::class, 'index'])->name('reviews.index');
        Route::put('reviews/{review}/approve', [ShopReviewController::class, 'approve'])->name('reviews.approve');
        Route::put('reviews/{review}/reject', [ShopReviewController::class, 'reject'])->name('reviews.reject');
        Route::put('reviews/{review}/reply', [ShopReviewController::class, 'reply'])->name('reviews.reply');
        Route::delete('reviews/{review}', [ShopReviewController::class, 'destroy'])->name('reviews.destroy');
        
        // Loyalty Settings
        Route::get('loyalty', [ShopCustomerController::class, 'loyalty'])->name('loyalty.index');
        Route::put('loyalty', [ShopCustomerController::class, 'updateLoyalty'])->name('loyalty.update');
        
        // Settings (separate controller)
        Route::get('settings', [ShopSettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [ShopSettingsController::class, 'update'])->name('settings.update');
    });
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('admin.register');
    Route::post('/register', [AuthController::class, 'register'])->name('admin.register.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', function () {
            return redirect('/admin/dashboard');
        });
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('/upload-image', [PostController::class, 'uploadImage'])->name('admin.upload-image');
        
        // Users Management
        Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
        Route::post('users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('admin.users.toggle-admin');
        Route::post('users/{user}/assign-role', [UserController::class, 'assignRole'])->name('admin.users.assign-role');
        
        // Roles Management
        Route::resource('roles', RoleController::class)->names('admin.roles');
        
        // Posts
        Route::resource('posts', PostController::class)->names('admin.posts');
        Route::post('posts/{post}/toggle-status', [PostController::class, 'toggleStatus'])->name('admin.posts.toggle-status');
        
        // Categories, Tags, Comments
        Route::resource('categories', CategoryController::class)->names('admin.categories');
        Route::resource('tags', TagController::class)->names('admin.tags');
        Route::resource('comments', CommentController::class)->names('admin.comments');
        Route::post('comments/{comment}/approve', [CommentController::class, 'approve'])->name('admin.comments.approve');
        Route::post('comments/{comment}/spam', [CommentController::class, 'spam'])->name('admin.comments.spam');
        Route::post('comments/{comment}/reply', [CommentController::class, 'reply'])->name('admin.comments.reply');
        
        // Settings
        Route::get('settings', [SettingController::class, 'index'])->name('admin.settings');
        Route::post('settings', [SettingController::class, 'update'])->name('admin.settings.update');
        
        // Profile
        Route::get('profile', [ProfileController::class, 'index'])->name('admin.profile');
        Route::put('profile', [ProfileController::class, 'update'])->name('admin.profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');
        
        // Pages, Sliders, Galleries
        Route::resource('pages', PageController::class)->names('admin.pages');
        Route::resource('sliders', SliderController::class)->names('admin.sliders');
        Route::post('sliders/{slider}/toggle-active', [SliderController::class, 'toggleActive'])->name('admin.sliders.toggle-active');
        Route::resource('galleries', GalleryController::class)->names('admin.galleries');
        
        // Shop Management
        Route::resource('shops', AdminShopController::class)->names('admin.shops');
        Route::post('shops/{shop}/toggle-status', [AdminShopController::class, 'toggleStatus'])->name('admin.shops.toggle-status');
        Route::get('shops/{shop}/subscription', [AdminShopController::class, 'manageSubscription'])->name('admin.shops.subscription');
        Route::post('shops/{shop}/subscription', [AdminShopController::class, 'updateSubscription'])->name('admin.shops.subscription.update');
        
        // Subscription Plans
        Route::resource('shop-plans', ShopSubscriptionPlanController::class, ['parameters' => ['shop-plans' => 'plan']])->names('admin.shop-plans');
        Route::post('shop-plans/{plan}/toggle-status', [ShopSubscriptionPlanController::class, 'toggleStatus'])->name('admin.shop-plans.toggle-status');
        
        // Shop Global Settings
        Route::get('shop-settings', [\App\Http\Controllers\Admin\ShopGlobalSettingsController::class, 'index'])->name('admin.shop-settings.index');
        Route::put('shop-settings', [\App\Http\Controllers\Admin\ShopGlobalSettingsController::class, 'update'])->name('admin.shop-settings.update');
        
        // Developer Info
        Route::get('developer', [DeveloperController::class, 'index'])->name('admin.developer');
        Route::post('developer', [DeveloperController::class, 'update'])->name('admin.developer.update');
    });
});

// Public Shop Routes
Route::get('/shops', [PublicShopController::class, 'allShops'])->name('shops.index');

// Individual Shop Routes (must be last to avoid conflicts)
Route::prefix('shop/{shop:slug}')->name('shop.')->group(function () {
    Route::get('/', [PublicShopController::class, 'show'])->name('show');
    Route::get('/products', [PublicShopController::class, 'products'])->name('products');
    Route::get('/product/{product:slug}', [PublicShopController::class, 'product'])->name('product');
    Route::get('/category/{category:slug}', [PublicShopController::class, 'category'])->name('category');
    
    // Cart
    Route::get('/cart', [PublicShopController::class, 'cart'])->name('cart');
    Route::get('/cart/add/{product:slug}', function (Shop $shop, ShopProduct $product) {
        return redirect()->route('shop.product', [$shop->slug, $product->slug])
            ->with('error', 'Please use the add to cart button on the product page.');
    });
    Route::post('/cart/add/{product:slug}', [PublicShopController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/update', [PublicShopController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove', [PublicShopController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/coupon', [PublicShopController::class, 'applyCoupon'])->name('cart.coupon');
    
    // Checkout
    Route::get('/checkout', [PublicShopController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [PublicShopController::class, 'placeOrder'])->name('checkout.process');
    Route::get('/order/{orderNumber}', [PublicShopController::class, 'orderConfirmation'])->name('order.confirmation');
    
    // Coupon
    Route::post('/coupon/apply', [PublicShopController::class, 'applyCoupon'])->name('coupon.apply');
    
    // Customer Auth
    Route::get('/login', [PublicShopController::class, 'customerLogin'])->name('login');
    Route::post('/login', [PublicShopController::class, 'customerLoginSubmit'])->name('login.submit');
    Route::post('/register', [PublicShopController::class, 'customerRegister'])->name('register');
    Route::get('/account', [PublicShopController::class, 'customerAccount'])->name('account');
    Route::get('/orders', [PublicShopController::class, 'customerOrders'])->name('orders');
    Route::post('/logout', [PublicShopController::class, 'customerLogout'])->name('logout');
    
    // Categories & Search
    Route::get('/categories', [PublicShopController::class, 'categories'])->name('categories');
    Route::get('/search', [PublicShopController::class, 'search'])->name('search');
    Route::get('/offers', [PublicShopController::class, 'offers'])->name('offers');
    Route::get('/offer/{offer}', [PublicShopController::class, 'offer'])->name('offer');
    Route::get('/contact', [PublicShopController::class, 'contact'])->name('contact');
    
    // Reviews
    Route::post('/review/{product}', [PublicShopController::class, 'submitReview'])->name('review.store');
});
