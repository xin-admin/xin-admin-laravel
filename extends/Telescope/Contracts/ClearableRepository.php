<?php

namespace Xin\Telescope\Contracts;

interface ClearableRepository
{
    /**
     * Clear all of the entries.
     *
     * @return void
     */
    public function clear(): void;
}
