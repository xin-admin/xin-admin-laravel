<?php

namespace App\Providers;

use App\Services\LengthAwarePaginatorService;
use App\Services\SysSettingService;
use Illuminate\Foundation\Exceptions\Handler as ExceptionsHandler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->singleton(SysSettingService::class, SysSettingService::class);

        $this->app->bind('Illuminate\Pagination\LengthAwarePaginator', function ($app, $options) {
            return new LengthAwarePaginatorService(
                $options['items'],
                $options['total'],
                $options['perPage'],
                $options['currentPage'],
                $options['options']
            );
        });

        $this->app->bind(ExceptionsHandler::class, \App\Exceptions\ExceptionsHandler::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!$this->app->runningInConsole()) {
            SysSettingService::refreshSettings();
        }
    }
}
