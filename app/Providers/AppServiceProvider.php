<?php

namespace App\Providers;

use App\Common;
use App\Services\LengthAwarePaginatorService;
use App\Services\SettingService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->singleton(SettingService::class, SettingService::class);

        $this->app->bind('Illuminate\Pagination\LengthAwarePaginator', function ($app, $options) {
            return new LengthAwarePaginatorService(
                $options['items'],
                $options['total'],
                $options['perPage'],
                $options['currentPage'],
                $options['options']
            );
        });

        $this->app->bind(\Illuminate\Foundation\Exceptions\Handler::class, \App\Exceptions\ExceptionsHandler::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!$this->app->runningInConsole()) {
            SettingService::refreshSettings();
        }
    }
}
