<?php

namespace Xin\Telescope\Contracts;

use Illuminate\Support\Collection;
use Laravel\Telescope\EntryUpdate;
use Xin\Telescope\EntryResult;
use Xin\Telescope\IncomingEntry;
use Xin\Telescope\Storage\EntryQueryOptions;

interface EntriesRepository
{
    /**
     * Return an entry with the given ID.
     */
    public function find(mixed $id): EntryResult;

    /**
     * Return all the entries of a given type.
     *
     * @return Collection|EntryResult[]
     */
    public function get(?string $type, EntryQueryOptions $options): array|Collection;

    /**
     * Store the given entries.
     *
     * @param Collection|IncomingEntry[]  $entries
     * @return void
     */
    public function store(Collection $entries): void;

    /**
     * Store the given entry updates and return the failed updates.
     *
     * @param Collection|EntryUpdate[]  $updates
     * @return Collection|null
     */
    public function update(Collection $updates): ?Collection;

    /**
     * Load the monitored tags from storage.
     */
    public function loadMonitoredTags(): void;

    /**
     * Determine if any of the given tags are currently being monitored.
     */
    public function isMonitoring(array $tags): bool;

    /**
     * Get the list of tags currently being monitored.
     */
    public function monitoring(): array;

    /**
     * Begin monitoring the given list of tags.
     */
    public function monitor(array $tags): void;

    /**
     * Stop monitoring the given list of tags.
     */
    public function stopMonitoring(array $tags): void;
}
