<?php

namespace Xin\Telescope\Watchers;

abstract class Watcher
{
    /**
     * The configured watcher options.
     *
     * @var array
     */
    public array $options = [];

    /**
     * Create a new watcher instance.
     *
     * @param  array  $options
     * @return void
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * Register the watcher.
     */
    abstract public function register($app);
}
