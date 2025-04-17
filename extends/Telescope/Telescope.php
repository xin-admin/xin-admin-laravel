<?php

namespace Xin\Telescope;

use Closure;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Str;
use Illuminate\Support\Testing\Fakes\EventFake;
use Xin\Telescope\Contracts\EntriesRepository;
use Throwable;

class Telescope
{
    use ListensForStorageOpportunities,
        RegistersWatchers;

    /**
     * The callbacks that filter the entries that should be recorded.
     */
    public static array $filterUsing = [];

    /**
     * The callbacks that filter the batches that should be recorded.
     */
    public static array $filterBatchUsing = [];

    /**
     * The callback executed after queuing a new entry.
     * @var Closure
     */
    public static $afterRecordingHook;

    /**
     * The callbacks executed after storing the entries.
     */
    public static Closure|array $afterStoringHooks = [];

    /**
     * The list of queued entries to be stored.
     */
    public static array $entriesQueue = [];


    /**
     * The list of hidden request headers.
     */
    public static array $hiddenRequestHeaders = [
        'authorization',
        'php-auth-pw',
    ];

    /**
     * The list of hidden request parameters.
     */
    public static array $hiddenRequestParameters = [
        'password',
        'password_confirmation',
    ];

    /**
     * The list of hidden response parameters.
     */
    public static array $hiddenResponseParameters = [];

    /**
     * Indicates if Telescope should ignore events fired by Laravel.
     */
    public static bool $ignoreFrameworkEvents = true;

    /**
     * Indicates if Telescope should record entries.
     */
    public static bool $shouldRecord = false;

    /**
     * Register the Telescope watchers and start recording if necessary.
     */
    public static function start($app): void
    {
        if (! config('telescope.enabled')) {
            return;
        }

        static::registerWatchers($app);

        if (! static::runningWithinOctane() &&
            (static::runningApprovedArtisanCommand($app) ||
            static::handlingApprovedRequest($app))
        ) {
            static::startRecording();
        }
    }

    /**
     * Determine if Telescope is running within Octane.
     */
    protected static function runningWithinOctane(): bool
    {
        return isset($_SERVER['LARAVEL_OCTANE']);
    }

    /**
     * Determine if the application is running an approved command.
     */
    protected static function runningApprovedArtisanCommand($app): bool
    {
        return $app->runningInConsole() && ! in_array(
            $_SERVER['argv'][1] ?? null,
            array_merge([
                // 'migrate',
                'migrate:rollback',
                'migrate:fresh',
                // 'migrate:refresh',
                'migrate:reset',
                'migrate:install',
                'package:discover',
                'queue:listen',
                'queue:work',
                'horizon',
                'horizon:work',
                'horizon:supervisor',
            ], config('telescope.ignoreCommands', []), config('telescope.ignore_commands', []))
        );
    }

    /**
     * Determine if the application is handling an approved request.
     */
    protected static function handlingApprovedRequest($app): bool
    {
        if ($app->runningInConsole()) {
            return false;
        }

        return static::requestIsToApprovedDomain($app['request']) &&
            static::requestIsToApprovedUri($app['request']);
    }

    /**
     * Determine if the request is to an approved domain.
     */
    protected static function requestIsToApprovedDomain(Request $request): bool
    {
        return is_null(config('telescope.domain')) ||
            config('telescope.domain') !== $request->getHost();
    }

    /**
     * Determine if the request is to an approved URI.
     */
    protected static function requestIsToApprovedUri(Request $request): bool
    {
        if (! empty($only = config('telescope.only_paths', []))) {
            return $request->is($only);
        }

        return ! $request->is(
            collect([
                'telescope-api*',
                'vendor/telescope*',
                (config('horizon.path') ?? 'horizon').'*',
                'vendor/horizon*',
            ])
            ->merge(config('telescope.ignore_paths', []))
            ->unless(is_null(config('telescope.path')), function ($paths) {
                return $paths->prepend(config('telescope.path').'*');
            })
            ->all()
        );
    }

    /**
     * Start recording entries.
     */
    public static function startRecording(): void
    {
        $recordingPaused = false;

        try {
            $recordingPaused = cache('telescope:pause-recording');
        } catch (Exception) {
            //
        }

        static::$shouldRecord = ! $recordingPaused;
    }

    /**
     * Stop recording entries.
     */
    public static function stopRecording(): void
    {
        static::$shouldRecord = false;
    }

    /**
     * Execute the given callback without recording Telescope entries.
     *
     * @param  callable  $callback
     */
    public static function withoutRecording($callback): mixed
    {
        $shouldRecord = static::$shouldRecord;

        static::$shouldRecord = false;

        try {
            return call_user_func($callback);
        } finally {
            static::$shouldRecord = $shouldRecord;
        }
    }

    /**
     * Determine if Telescope is recording.
     */
    public static function isRecording(): bool
    {
        return static::$shouldRecord && ! app('events') instanceof EventFake;
    }

    /**
     * Record the given entry.
     */
    public static function record(IncomingEntry $entry): void
    {
        if (! static::isRecording()) {
            return;
        }

        static::withoutRecording(function () use ($entry) {
            if (collect(static::$filterUsing)->every->__invoke($entry)) {
                static::$entriesQueue[] = $entry;
            }

            if (static::$afterRecordingHook) {
                call_user_func(static::$afterRecordingHook, new static, $entry);
            }
        });
    }

    /**
     * Flush all entries in the queue.
     */
    public static function flushEntries(): static
    {
        static::$entriesQueue = [];

        return new static;
    }

    /**
     * Record the given exception.
     */
    public static function catch($e, array $tags = []): void
    {
        event(new MessageLogged('error', $e->getMessage(), [
            'exception' => $e,
            'telescope' => $tags,
        ]));
    }

    /**
     * Set the callback that filters the entries that should be recorded.
     */
    public static function filter(Closure $callback): static
    {
        static::$filterUsing[] = $callback;

        return new static;
    }

    /**
     * Set the callback that filters the batches that should be recorded.
     */
    public static function filterBatch(Closure $callback): static
    {
        static::$filterBatchUsing[] = $callback;

        return new static;
    }

    /**
     * Set the callback that will be executed after an entry is recorded in the queue.
     */
    public static function afterRecording(Closure $callback): static
    {
        static::$afterRecordingHook = $callback;

        return new static;
    }

    /**
     * Add a callback that will be executed after an entry is stored.
     */
    public static function afterStoring(Closure $callback): static
    {
        static::$afterStoringHooks[] = $callback;

        return new static;
    }

    /**
     * Store the queued entries and flush the queue.
     *
     * @param  Contracts\EntriesRepository  $storage
     * @return void
     */
    public static function store(EntriesRepository $storage): void
    {
        if (empty(static::$entriesQueue)) {
            return;
        }

        static::withoutRecording(function () use ($storage) {
            if (! collect(static::$filterBatchUsing)->every->__invoke(collect(static::$entriesQueue))) {
                static::flushEntries();
            }

            try {
                $batchId = Str::orderedUuid()->toString();

                $storage->store(collect(static::$entriesQueue));

                collect(static::$afterStoringHooks)->every->__invoke(static::$entriesQueue, $batchId);
            } catch (Throwable $e) {
                app(ExceptionHandler::class)->report($e);
            }
        });

        static::$entriesQueue = [];
    }

    /**
     * Hide the given request header.
     */
    public static function hideRequestHeaders(array $headers): static
    {
        static::$hiddenRequestHeaders = array_values(array_unique(array_merge(
            static::$hiddenRequestHeaders,
            $headers
        )));

        return new static;
    }

    /**
     * Hide the given request parameters.
     */
    public static function hideRequestParameters(array $attributes): static
    {
        static::$hiddenRequestParameters = array_merge(
            static::$hiddenRequestParameters,
            $attributes
        );

        return new static;
    }

    /**
     * Hide the given response parameters.
     */
    public static function hideResponseParameters(array $attributes): static
    {
        static::$hiddenResponseParameters = array_values(array_unique(array_merge(
            static::$hiddenResponseParameters,
            $attributes
        )));

        return new static;
    }

    /**
     * Specifies that Telescope should record events fired by Laravel.
     */
    public static function recordFrameworkEvents(): static
    {
        static::$ignoreFrameworkEvents = false;

        return new static;
    }
}
