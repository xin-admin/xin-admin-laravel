<?php

namespace App\Providers;

use App\Ai\Boots\Reasonix;
use App\Models\SysAccessToken;
use Illuminate\Foundation\DevCommands;
use Illuminate\Support\ServiceProvider;
use Laravel\Boost\Boost;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {

    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Sanctum::usePersonalAccessTokenModel(SysAccessToken::class);
        // Run all processes except the queue worker...
        DevCommands::except('vite');
        // reasonix
        Boost::registerAgent('reasonix', Reasonix::class);
    }



}
