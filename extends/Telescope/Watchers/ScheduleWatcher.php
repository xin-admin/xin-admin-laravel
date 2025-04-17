<?php

namespace Xin\Telescope\Watchers;

use Illuminate\Console\Events\CommandStarting;
use Illuminate\Console\Scheduling\CallbackEvent;
use Illuminate\Console\Scheduling\Schedule;
use Xin\Telescope\EntryType;
use Xin\Telescope\IncomingEntry;
use Xin\Telescope\Telescope;

class ScheduleWatcher extends Watcher
{
    /**
     * Register the watcher.
     */
    public function register($app)
    {
        $app['events']->listen(CommandStarting::class, [$this, 'recordCommand']);
    }

    /**
     * Record a scheduled command was executed.
     */
    public function recordCommand(CommandStarting $event): void
    {
        if (! Telescope::isRecording() ||
            $event->command !== 'schedule:run' &&
            $event->command !== 'schedule:finish') {
            return;
        }

        collect(app(Schedule::class)->events())->each(function ($event) {
            $event->then(function () use ($event) {
                Telescope::record(IncomingEntry::make([
                    'command' => $event instanceof CallbackEvent ? 'Closure' : $event->command,
                    'description' => $event->description,
                    'expression' => $event->expression,
                    'timezone' => $event->timezone,
                    'user' => $event->user,
                    'output' => $this->getEventOutput($event),
                ], EntryType::SCHEDULED_TASK));
            });
        });
    }

    /**
     * Get the output for the scheduled event.
     */
    protected function getEventOutput($event): ?string
    {
        if (! $event->output ||
            $event->output === $event->getDefaultOutput() ||
            $event->shouldAppendOutput ||
            ! file_exists($event->output)) {
            return '';
        }

        return trim(file_get_contents($event->output));
    }
}
