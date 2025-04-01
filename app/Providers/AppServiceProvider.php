<?php

namespace App\Providers;

use App\Service\LengthAwarePaginatorService;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        Handler::class => \App\Exceptions\Handler::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('Illuminate\Pagination\LengthAwarePaginator', function ($app, $options) {
            return new LengthAwarePaginatorService($options['items'], $options['total'], $options['perPage'], $options['currentPage'], $options['options']);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
