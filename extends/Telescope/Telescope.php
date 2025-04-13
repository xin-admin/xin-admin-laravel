<?php

namespace Xin\Telescope;

use Closure;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Testing\Fakes\EventFake;
use Xin\Telescope\Contracts\EntriesRepository;
use Xin\Telescope\Contracts\TerminableRepository;
use Xin\Telescope\Jobs\ProcessPendingUpdates;
use Throwable;

class Telescope
{
    use ExtractsMailableTags,
        ListensForStorageOpportunities,
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
     */
    public static Closure $afterRecordingHook;

    /**
     * The callbacks executed after storing the entries.
     */
    public static Closure|array $afterStoringHooks = [];

    /**
     * The callbacks that add tags to the record.
     */
    public static array $tagUsing = [];

    /**
     * The list of queued entries to be stored.
     */
    public static array $entriesQueue = [];

    /**
     * The list of queued entry updates.
     */
    public static array $updatesQueue = [];

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
     * Indicates if Telescope should use the dark theme.
     */
    public static bool $useDarkTheme = false;

    /**
     * Indicates if Telescope should record entries.
     */
    public static bool $shouldRecord = false;

    /**
     * Register the Telescope watchers and start recording if necessary.
     *
     * @param  Application  $app
     */
    public static function start($app): void
    {
        if (! config('telescope.enabled')) {
            return;
        }

        static::registerWatchers($app);

        static::registerMailableTagExtractor();

        if (! static::runningWithinOctane($app) &&
            (static::runningApprovedArtisanCommand($app) ||
            static::handlingApprovedRequest($app))
        ) {
            static::startRecording($loadMonitoredTags = false);
        }
    }

    /**
     * Determine if Telescope is running within Octane.
     *
     * @param  Application  $app
     */
    protected static function runningWithinOctane($app): bool
    {
        return isset($_SERVER['LARAVEL_OCTANE']);
    }

    /**
     * Determine if the application is running an approved command.
     *
     * @param  Application  $app
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
     *
     * @param  Application  $app
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
    public static function startRecording(bool $loadMonitoredTags = true): void
    {
        if ($loadMonitoredTags) {
            app(EntriesRepository::class)->loadMonitoredTags();
        }

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
    protected static function record(string $type, IncomingEntry $entry): void
    {
        if (! static::isRecording()) {
            return;
        }

        try {
            if (Auth::hasResolvedGuards() && Auth::hasUser()) {
                $entry->user(Auth::user());
            }
        } catch (Throwable $e) {
            // Do nothing.
        }

        $entry->type($type)->tags(Arr::collapse(array_map(function ($tagCallback) use ($entry) {
            return $tagCallback($entry);
        }, static::$tagUsing)));

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
     * Record the given entry update.
     */
    public static function recordUpdate(EntryUpdate $update): void
    {
        if (static::$shouldRecord) {
            static::$updatesQueue[] = $update;
        }
    }

    /**
     * Record the Batch entry.
     */
    public static function recordBatch(IncomingEntry $entry): void
    {
        static::record(EntryType::BATCH, $entry);
    }

    /**
     * Record the Cache entry.
     */
    public static function recordCache(IncomingEntry $entry): void
    {
        static::record(EntryType::CACHE, $entry);
    }

    /**
     * Record the Command entry.
     */
    public static function recordCommand(IncomingEntry $entry): void
    {
        static::record(EntryType::COMMAND, $entry);
    }

    /**
     * Record the Dump entry.
     */
    public static function recordDump(IncomingEntry $entry): void
    {
        static::record(EntryType::DUMP, $entry);
    }

    /**
     * Record the Event entry.
     */
    public static function recordEvent(IncomingEntry $entry): void
    {
        static::record(EntryType::EVENT, $entry);
    }

    /**
     * Record the Exception entry.
     */
    public static function recordException(IncomingEntry $entry): void
    {
        static::record(EntryType::EXCEPTION, $entry);
    }

    /**
     * Record the Gate entry.
     */
    public static function recordGate(IncomingEntry $entry): void
    {
        static::record(EntryType::GATE, $entry);
    }

    /**
     * Record the Job entry.
     */
    public static function recordJob(IncomingEntry $entry): void
    {
        static::record(EntryType::JOB, $entry);
    }

    /**
     * Record the Log entry.
     */
    public static function recordLog(IncomingEntry $entry): void
    {
        static::record(EntryType::LOG, $entry);
    }

    /**
     * Record the Mail entry.
     */
    public static function recordMail(IncomingEntry $entry): void
    {
        static::record(EntryType::MAIL, $entry);
    }

    /**
     * Record the Notification entry.
     */
    public static function recordNotification(IncomingEntry $entry): void
    {
        static::record(EntryType::NOTIFICATION, $entry);
    }

    /**
     * Record the Query entry.
     */
    public static function recordQuery(IncomingEntry $entry): void
    {
        static::record(EntryType::QUERY, $entry);
    }

    /**
     * Record the ModelEvent entry.
     */
    public static function recordModelEvent(IncomingEntry $entry): void
    {
        static::record(EntryType::MODEL, $entry);
    }

    /**
     * Record the Redis entry.
     */
    public static function recordRedis(IncomingEntry $entry): void
    {
        static::record(EntryType::REDIS, $entry);
    }

    /**
     * Record the Request entry.
     */
    public static function recordRequest(IncomingEntry $entry): void
    {
        static::record(EntryType::REQUEST, $entry);
    }

    /**
     * Record the ScheduledCommand entry.
     */
    public static function recordScheduledCommand(IncomingEntry $entry): void
    {
        static::record(EntryType::SCHEDULED_TASK, $entry);
    }

    /**
     * Record the ClientRequest entry.
     */
    public static function recordClientRequest(IncomingEntry $entry): void
    {
        static::record(EntryType::CLIENT_REQUEST, $entry);
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
     * Add a callback that adds tags to the record.
     */
    public static function tag(Closure $callback): static
    {
        static::$tagUsing[] = $callback;

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
        if (empty(static::$entriesQueue) && empty(static::$updatesQueue)) {
            return;
        }

        static::withoutRecording(function () use ($storage) {
            if (! collect(static::$filterBatchUsing)->every->__invoke(collect(static::$entriesQueue))) {
                static::flushEntries();
            }

            try {
                $batchId = Str::orderedUuid()->toString();

                $storage->store(static::collectEntries($batchId));

                $updateResult = $storage->update(static::collectUpdates($batchId)) ?: Collection::make();

                if (! isset($_ENV['VAPOR_SSM_PATH'])) {
                    $delay = config('telescope.queue.delay');

                    $updateResult->whenNotEmpty(fn ($pendingUpdates) => rescue(fn () => ProcessPendingUpdates::dispatch(
                        $pendingUpdates,
                    )->onConnection(
                        config('telescope.queue.connection')
                    )->onQueue(
                        config('telescope.queue.queue')
                    )->delay(is_numeric($delay) && $delay > 0 ? now()->addSeconds($delay) : null)));
                }

                if ($storage instanceof TerminableRepository) {
                    $storage->terminate();
                }

                collect(static::$afterStoringHooks)->every->__invoke(static::$entriesQueue, $batchId);
            } catch (Throwable $e) {
                app(ExceptionHandler::class)->report($e);
            }
        });

        static::$entriesQueue = [];
        static::$updatesQueue = [];
    }

    /**
     * Collect the entries for storage.
     */
    protected static function collectEntries(string $batchId): Collection
    {
        return collect(static::$entriesQueue)
            ->each(function ($entry) use ($batchId) {
                $entry->batchId($batchId);

                if ($entry->isDump()) {
                    $entry->assignEntryPointFromBatch(static::$entriesQueue);
                }
            });
    }

    /**
     * Collect the updated entries for storage.
     */
    protected static function collectUpdates(string $batchId): Collection
    {
        return collect(static::$updatesQueue)
            ->each(function ($entry) use ($batchId) {
                $entry->change(['updated_batch_id' => $batchId]);
            });
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

    /**
     * Specifies that Telescope should use the dark theme.
     */
    public static function night(): static
    {
        static::$useDarkTheme = true;

        return new static;
    }

    /**
     * Get the default JavaScript variables for Telescope.
     */
    public static function scriptVariables(): array
    {
        return [
            'path' => config('telescope.path'),
            'timezone' => config('app.timezone'),
            'recording' => ! cache('telescope:pause-recording'),
        ];
    }
}
