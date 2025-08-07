<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

Route::prefix('v1')->group(function () {
    Route::get('/checkquanity/{productId}/{sizeId}', [ProductController::class, 'checkSizeQuanity']);
    Route::get('/get-categories', [ProductController::class, 'getCategories'])->name('product.getCategories');
    Route::get('/setting', [ProductController::class, 'setting'])->name('header.setting');
    Route::get('/get-products', [ProductController::class, 'getProducts'])->name('product.get-products');
    
});