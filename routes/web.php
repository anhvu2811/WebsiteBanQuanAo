<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/category', [App\Http\Controllers\CategoryController::class, 'index'])->name('category.index');

Route::get('/product', [App\Http\Controllers\ProductController::class, 'index'])->name('product.index');

Route::get('/login', [App\Http\Controllers\LoginController::class, 'index'])->name('login.index');
Route::get('/register', [App\Http\Controllers\RegisterController::class, 'index'])->name('register.index');

