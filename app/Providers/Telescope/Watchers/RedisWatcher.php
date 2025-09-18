<?php

namespace App\Providers\Telescope\Watchers;

use App\Providers\Telescope\EntryType;
use App\Providers\Telescope\IncomingEntry;
use App\Providers\Telescope\Telescope;
use Illuminate\Redis\Events\CommandExecuted;

class RedisWatcher extends Watcher
{
    /**
     * Register the watcher.
     */
    public function register($app): void
    {
        if (! $app->bound('redis')) {
            return;
        }

        $app['events']->listen(CommandExecuted::class, [$this, 'recordCommand']);

        foreach ((array) $app['redis']->connections() as $connection) {
            $connection->setEventDispatcher($app['events']);
        }

        $app['redis']->enableEvents();
    }

    /**
     * Record a Redis command was executed.
     *
     * @param CommandExecuted $event
     * @return void
     */
    public function recordCommand(CommandExecuted $event): void
    {
        if (! Telescope::isRecording() || $this->shouldIgnore($event)) {
            return;
        }

        Telescope::record(IncomingEntry::make([
            'connection' => $event->connectionName,
            'command' => $this->formatCommand($event->command, $event->parameters),
            'time' => number_format($event->time, 2, '.', ''),
        ], EntryType::REDIS));
    }

    /**
     * Format the given Redis command.
     */
    private function formatCommand(string $command, array $parameters): string
    {
        $parameters = collect($parameters)->map(function ($parameter) {
            if (is_array($parameter)) {
                return collect($parameter)->map(function ($value, $key) {
                    if (is_array($value)) {
                        return json_encode($value);
                    }

                    return is_int($key) ? $value : "{$key} {$value}";
                })->implode(' ');
            }

            return $parameter;
        })->implode(' ');

        return "{$command} {$parameters}";
    }

    /**
     * Determine if the event should be ignored.
     */
    private function shouldIgnore(mixed $event): bool
    {
        return in_array($event->command, [
            'pipeline', 'transaction',
        ]);
    }
}
