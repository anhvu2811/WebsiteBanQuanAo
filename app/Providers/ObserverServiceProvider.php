<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\Setting;
use App\Observers\ProductObserver;
use App\Observers\ProductSizeObserver;
use App\Observers\ProductImageObserver;
use App\Observers\OrderObserver;
use App\Observers\SettingObserver;

class ObserverServiceProvider extends ServiceProvider
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
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        ProductSize::observe(ProductSizeObserver::class);
        ProductImage::observe(ProductImageObserver::class);
        Order::observe(OrderObserver::class);
        Setting::observe(SettingObserver::class);
    }
}
