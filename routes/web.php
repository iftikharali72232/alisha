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
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\BlogController;

Route::get('/', [BlogController::class, 'index'])->name('home');

// Blog Frontend Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{slug}/comment', [BlogController::class, 'storeComment'])->name('blog.comment');
Route::get('/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/gallery', [BlogController::class, 'gallery'])->name('blog.gallery');
Route::get('/page/{slug}', [BlogController::class, 'page'])->name('blog.page');
Route::get('/about', [BlogController::class, 'about'])->name('blog.about');
Route::get('/contact', [BlogController::class, 'contact'])->name('blog.contact');
Route::post('/contact', [BlogController::class, 'sendContact'])->name('blog.contact.send');

Route::middleware('auth')->group(function () {
    Route::get('/user', [UserDashboardController::class, 'index'])->name('user.dashboard');
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
        Route::resource('users', UserController::class)->except(['create', 'store', 'show', 'edit', 'update', 'destroy'])->names('admin.users');
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
        Route::post('users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('admin.users.toggle-admin');
        Route::resource('posts', PostController::class)->names('admin.posts');
        Route::post('posts/{post}/toggle-status', [PostController::class, 'toggleStatus'])->name('admin.posts.toggle-status');
        Route::resource('categories', CategoryController::class)->names('admin.categories');
        Route::resource('tags', TagController::class)->names('admin.tags');
        Route::resource('comments', CommentController::class)->names('admin.comments');
        Route::post('comments/{comment}/approve', [CommentController::class, 'approve'])->name('admin.comments.approve');
        Route::post('comments/{comment}/spam', [CommentController::class, 'spam'])->name('admin.comments.spam');
        Route::get('settings', [SettingController::class, 'index'])->name('admin.settings');
        Route::post('settings', [SettingController::class, 'update'])->name('admin.settings.update');
        Route::get('profile', [ProfileController::class, 'index'])->name('admin.profile');
        Route::put('profile', [ProfileController::class, 'update'])->name('admin.profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');
        Route::resource('pages', PageController::class)->names('admin.pages');
        Route::resource('sliders', SliderController::class)->names('admin.sliders');
        Route::post('sliders/{slider}/toggle-active', [SliderController::class, 'toggleActive'])->name('admin.sliders.toggle-active');
        Route::resource('galleries', GalleryController::class)->names('admin.galleries');
    });
});
