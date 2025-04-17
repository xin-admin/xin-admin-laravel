<?php

namespace Xin\Telescope\Watchers;

use Illuminate\Bus\Events\BatchDispatched;
use Illuminate\Support\Facades\Event;
use Xin\Telescope\EntryType;
use Xin\Telescope\IncomingEntry;
use Xin\Telescope\Telescope;

class BatchWatcher extends Watcher
{
    /**
     * Register the watcher.
     */
    public function register($app): void
    {
        $app['events']->listen(BatchDispatched::class, [$this, 'recordBatch']);
    }

    /**
     * Record a job being created.
     */
    public function recordBatch(BatchDispatched $event): void
    {
        if (! Telescope::isRecording()) {
            return;
        }

        $content = array_merge($event->batch->toArray(), [
            'queue' => $event->batch->options['queue'] ?? 'default',
            'connection' => $event->batch->options['connection'] ?? 'default',
            'allowsFailures' => $event->batch->allowsFailures(),
        ]);

        $entry = IncomingEntry::make($content, EntryType::BATCH);

        Telescope::record($entry);
    }
}
