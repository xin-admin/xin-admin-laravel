<?php

namespace Xin\Telescope\Watchers;

use Illuminate\Cache\Events\CacheHit;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Cache\Events\KeyForgotten;
use Illuminate\Cache\Events\KeyWritten;
use Illuminate\Support\Str;
use Xin\Telescope\EntryType;
use Xin\Telescope\IncomingEntry;
use Xin\Telescope\Telescope;

class CacheWatcher extends Watcher
{
    /**
     * Register the watcher.
     *
     * @param  mixed $app
     * @return void
     */
    public function register($app): void
    {
        $app['events']->listen(CacheHit::class, [$this, 'recordCacheHit']);
        $app['events']->listen(CacheMissed::class, [$this, 'recordCacheMissed']);

        $app['events']->listen(KeyWritten::class, [$this, 'recordKeyWritten']);
        $app['events']->listen(KeyForgotten::class, [$this, 'recordKeyForgotten']);
    }

    /**
     * Record a cache key was found.
     *
     * @param CacheHit $event
     * @return void
     */
    public function recordCacheHit(CacheHit $event): void
    {
        if (! Telescope::isRecording() || $this->shouldIgnore($event)) {
            return;
        }

        $entry = IncomingEntry::make([
            'type' => 'hit',
            'key' => $event->key,
            'value' => $this->formatValue($event),
        ], EntryType::CACHE);

        Telescope::record($entry);
    }

    /**
     * Record a missing cache key.
     *
     * @param CacheMissed $event
     * @return void
     */
    public function recordCacheMissed(CacheMissed $event): void
    {
        if (! Telescope::isRecording() || $this->shouldIgnore($event)) {
            return;
        }
        $entry = IncomingEntry::make([
            'type' => 'missed',
            'key' => $event->key,
        ], EntryType::CACHE);

        Telescope::record($entry);
    }

    /**
     * Record a cache key was updated.
     *
     * @param KeyWritten $event
     * @return void
     */
    public function recordKeyWritten(KeyWritten $event): void
    {
        if (! Telescope::isRecording() || $this->shouldIgnore($event)) {
            return;
        }
        $entry = IncomingEntry::make([
            'type' => 'set',
            'key' => $event->key,
            'value' => $this->formatValue($event),
            'expiration' => $event->seconds,
        ], EntryType::CACHE);

        Telescope::record($entry);
    }

    /**
     * Record a cache key was forgotten / removed.
     *
     * @param KeyForgotten $event
     * @return void
     */
    public function recordKeyForgotten(KeyForgotten $event): void
    {
        if (! Telescope::isRecording() || $this->shouldIgnore($event)) {
            return;
        }
        $entry = IncomingEntry::make([
            'type' => 'forget',
            'key' => $event->key,
        ], EntryType::CACHE);

        Telescope::record($entry);
    }

    /**
     * Determine the value of an event.
     */
    private function formatValue(mixed $event): mixed
    {
        return (! $this->shouldHideValue($event))
                    ? $event->value
                    : '********';
    }

    /**
     * Determine if the event value should be ignored.
     */
    private function shouldHideValue(mixed $event): bool
    {
        return Str::is(
            $this->options['hidden'] ?? [],
            $event->key
        );
    }

    /**
     * Determine if the event should be ignored.
     */
    private function shouldIgnore(mixed $event): bool
    {
        return Str::is([
            'illuminate:queue:restart',
            'framework/schedule*',
            'telescope:*',
        ], $event->key);
    }
}
