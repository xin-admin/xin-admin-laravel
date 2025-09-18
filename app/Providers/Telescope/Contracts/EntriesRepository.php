<?php

namespace App\Providers\Telescope\Contracts;

use App\Providers\Telescope\IncomingEntry;
use App\Providers\Telescope\Storage\EntryQueryOptions;
use Illuminate\Support\Collection;

interface EntriesRepository
{
    /**
     * Return all the entries of a given type.
     *
     * @param EntryQueryOptions $options
     * @return array
     */
    public function get(EntryQueryOptions $options): array;

    /**
     * Store the given entries.
     *
     * @param Collection|IncomingEntry[] $entries
     * @return void
     */
    public function store(array|Collection $entries): void;

    /**
     * Clear all of the entries.
     */
    public function clear(): void;
}
