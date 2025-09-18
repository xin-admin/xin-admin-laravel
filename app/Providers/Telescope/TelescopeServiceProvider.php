<?php

namespace App\Providers\Telescope;

use App\Providers\Telescope\Contracts\EntriesRepository;
use App\Providers\Telescope\Storage\StorageRepository;
use Illuminate\Support\ServiceProvider;

class TelescopeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        if (! config('telescope.enabled')) {
            return;
        }

        Telescope::start($this->app);
        Telescope::listenForStorageOpportunities($this->app);
    }

    /**
     * Register any package services.
     */
    public function register(): void
    {
        $this->registerDatabaseDriver();

        $this->hideSensitiveRequestDetails();

        Telescope::filter(function (IncomingEntry $entry) {
            return $entry->isFailedRequest() ||
                $entry->isQuery() ||
                $entry->isSlowQuery() ||
                $entry->isRequest() ||
                $entry->isCache() ||
                $entry->isRedis() ||
                $entry->isAuth();
        });
    }

    /**
     * Register the package database storage driver.
     */
    protected function registerDatabaseDriver(): void
    {
        $this->app->singleton(
            EntriesRepository::class, StorageRepository::class
        );
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }
}
