<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\NotificationController;
use App\Http\Middleware\CheckRole;

Route::get('/', [ProductController::class, 'getHotTrendProducts'])->name('page.index');
Route::get('/about', [SettingController::class, 'displayAbout'])->name('page.about');
Route::get('/news', [SettingController::class, 'displayNews'])->name('page.news');
Route::get('/account/login', [SettingController::class, 'displayLogin'])->name('login');
Route::get('/collections/all', [ProductController::class, 'collections'])->name('product.collections');
Route::get('/collections/{slug}-{id}', [ProductController::class, 'getProductDetail'])->where([
        'slug' => '.*',
        'id' => '[0-9]+'
    ])->name('product.getProductDetail');

Route::post('/account/login', [LoginController::class, 'login'])->name('login.index');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/get-cart-items', [CartController::class, 'getCartItemByUser'])->name('cart.get-cart-items');
    Route::post('/change-password',[LoginController::class, 'changePassword'])->name('change-password');
    Route::get('/get-cart', [CartController::class, 'getCart'])->name('cart.get-cart');
    Route::delete('remove-item/{id}', [CartController::class, 'removeItem'])->name('cart.remove-item');
    Route::post('/update-quantity/{id}/{quantity}', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');

    Route::get('/order-page', [OrderController::class, 'orderPage'])->name('page.order-page');
    Route::get('/infomation', [LoginController::class, 'information'])->name('page.information');
    Route::post('/update-infor', [LoginController::class, 'updateInfor'])->name('page.update-infor');
    Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('reset-password');

    // --------------------- Cart ---------------------
    Route::get('/cart', [CartController::class, 'cart'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{index}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkoutt', [CartController::class, 'checkout'])->name('cart.checkout');

    Route::middleware([CheckRole::class . ':admin,seller'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
        // --------------------- Category ---------------------
        Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
        Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
        Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
        
        // --------------------- Product ---------------------
        Route::get('/product', [ProductController::class, 'index'])->name('product.index');
        Route::get('/product/create', [ProductController::class, 'create'])->name('cproduct.create');
        Route::post('/product', [ProductController::class, 'store'])->name('product.store');
        Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
        Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
        
        Route::delete('/product/delete-image/{id}', [ProductController::class, 'deleteProductImage'])->name('product.deleteProductImage');
        Route::post('/product/update-image/{id}', [ProductController::class, 'updateProductImage'])->name('product.updateImage');
        Route::get('/product/{id}/sizes', [ProductController::class, 'showSizes']);
        
        // --------------------- Order ---------------------
        Route::get('/order', [OrderController::class, 'index'])->name('order.index');
        Route::delete('/order/{id}', [OrderController::class, 'destroy'])->name('order.destroy');
        Route::post('/order/add', [OrderController::class, 'createOrder'])->name('order.create');
        
        // --------------------- Setting ---------------------
        Route::get('/setting', [SettingController::class, 'edit'])->name('setting.edit');
        Route::put('/setting/update', [SettingController::class, 'update'])->name('setting.update');
        
        // --------------------- Banner ---------------------
        Route::get('/banner', [BannerController::class, 'edit'])->name('banner.edit');
        Route::put('/banner/update', [BannerController::class, 'update'])->name('banner.update');
    });

});

// Notification api
Route::get('get-notifications', [NotificationController::class, 'getUserNotifications'])->name('noti.get-notifications');
Route::post('mask-as-read/{id}', [NotificationController::class, 'maskAsRead'])->name('noti.mask-as-read');

// Stripe Payment
Route::post('/create-checkout-session', [PaymentController::class, 'createCheckoutSession'])->name('create.checkout');
Route::get('/success', [PaymentController::class, 'success'])->name('success');
Route::get('/cancel', [PaymentController::class, 'cancel'])->name('cancel');
Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');