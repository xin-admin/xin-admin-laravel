<?php

namespace Xin\Telescope\Contracts;

use DateTimeInterface;

interface PrunableRepository
{
    /**
     * Prune all of the entries older than the given date.
     */
    public function prune(DateTimeInterface $before, bool $keepExceptions): int;
}
