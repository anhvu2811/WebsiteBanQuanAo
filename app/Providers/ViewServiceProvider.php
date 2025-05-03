<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\CartItem;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        View::composer(['layouts.user.header', 'page.cart', 'page.checkout'], function ($view) {
            $cartCount = 0;
            $total = 0;
            $listCart = collect();
            if (Auth::check()) {
                $cartCount = CartItem::where('user_id', Auth::id())->where('status', 'pending')->count('user_id');
                $total = CartItem::where('user_id', Auth::id())
                            ->where('status', 'pending')
                            ->select(DB::raw('SUM(price * quantity) as total'))
                            ->value('total');
                $listCart = CartItem::where('user_id', Auth::id())->where('status', 'pending')->get();
            }
            $view->with('cartCount', $cartCount)
                 ->with('total', $total)
                 ->with('listCart', $listCart);
        });
    }
}
