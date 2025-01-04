<?php

namespace App\Providers;

use App\Service\IAuthorizeService;
use App\Service\impl\AuthorizeService;
use Illuminate\Support\ServiceProvider;

class AuthorizeProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(IAuthorizeService::class, function () {
            return new AuthorizeService;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
