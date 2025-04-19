<?php

namespace Xin\Telescope;

use Laravel\Octane\Events\RequestReceived;
use Laravel\Octane\Events\RequestTerminated;
use Xin\Telescope\Contracts\EntriesRepository;

trait ListensForStorageOpportunities
{

    /**
     * Register listeners that store the recorded Telescope entries.
     */
    public static function listenForStorageOpportunities($app): void
    {
        static::manageRecordingStateForOctane($app);
        static::storeEntriesBeforeTermination($app);
    }

    /**
     * Manage starting and stopping the recording state for Octane.
     */
    protected static function manageRecordingStateForOctane($app): void
    {
        $app['events']->listen(RequestReceived::class, function ($event) {
            if (static::requestIsToApprovedUri($event->request)) {
                static::startRecording();
            }
        });

        $app['events']->listen(RequestTerminated::class, function ($event) {
            static::stopRecording();
        });
    }

    /**
     * Store the entries in queue before the application termination.
     *
     * This handles storing entries for HTTP requests and Artisan commands.
     */
    protected static function storeEntriesBeforeTermination($app): void
    {
        $app->terminating(function () use ($app) {
            static::store($app[EntriesRepository::class]);
        });
    }
}
