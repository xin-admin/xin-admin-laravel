<?php

namespace Xin\Telescope;

use Illuminate\Foundation\Application;

trait RegistersWatchers
{
    /**
     * The class names of the registered watchers.
     */
    protected static array $watchers = [];

    /**
     * Determine if a given watcher has been registered.
     */
    public static function hasWatcher($class): bool
    {
        return in_array($class, static::$watchers);
    }

    /**
     * Register the configured Telescope watchers.
     */
    protected static function registerWatchers(Application $app): void
    {
        foreach (config('telescope.watchers') as $key => $watcher) {
            if (is_string($key) && $watcher === false) {
                continue;
            }

            if (is_array($watcher) && ! ($watcher['enabled'] ?? true)) {
                continue;
            }

            $watcher = $app->make(is_string($key) ? $key : $watcher, [
                'options' => is_array($watcher) ? $watcher : [],
            ]);

            static::$watchers[] = get_class($watcher);

            $watcher->register($app);
        }
    }
}
