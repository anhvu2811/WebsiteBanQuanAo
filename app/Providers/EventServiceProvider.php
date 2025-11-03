<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider; 
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Logout;
use App\Listeners\LogSuccessfulLogin;
use App\Listeners\LogSuccessfulLogout;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            LogSuccessfulLogin::class,
        ],
        Logout::class => [
            LogSuccessfulLogout::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
