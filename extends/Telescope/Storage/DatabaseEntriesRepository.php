<?php

namespace Xin\Telescope\Storage;

use DateTimeInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Xin\Telescope\Contracts\ClearableRepository;
use Xin\Telescope\Contracts\EntriesRepository;
use Xin\Telescope\Contracts\PrunableRepository;
use Xin\Telescope\Contracts\TerminableRepository;
use Xin\Telescope\EntryResult;
use Xin\Telescope\EntryType;
use Xin\Telescope\EntryUpdate;
use Xin\Telescope\IncomingEntry;

class DatabaseEntriesRepository implements EntriesRepository, ClearableRepository, PrunableRepository, TerminableRepository
{
    /**
     * The database connection name that should be used.
     */
    protected string $connection;

    /**
     * The number of entries that will be inserted at once into the database.
     */
    protected int $chunkSize = 1000;

    /**
     * The tags currently being monitored.
     */
    protected ?array $monitoredTags;

    /**
     * Create a new database repository.
     */
    public function __construct(string $connection, ?int $chunkSize = null)
    {
        $this->connection = $connection;

        if ($chunkSize) {
            $this->chunkSize = $chunkSize;
        }
    }

    /**
     * Find the entry with the given ID.
     */
    public function find(mixed $id): EntryResult
    {
        $entry = EntryModel::on($this->connection)->whereUuid($id)->firstOrFail();

        $tags = $this->table('telescope_entries_tags')
                        ->where('entry_uuid', $id)
                        ->pluck('tag')
                        ->all();

        return new EntryResult(
            $entry->uuid,
            null,
            $entry->batch_id,
            $entry->type,
            $entry->family_hash,
            $entry->content,
            $entry->created_at,
            $tags
        );
    }

    /**
     * Return all the entries of a given type.
     *
     * @param string|null $type
     * @param EntryQueryOptions $options
     * @return Collection|EntryResult[]
     */
    public function get(?string $type, EntryQueryOptions $options): array|Collection
    {
        return EntryModel::on($this->connection)
            ->withTelescopeOptions($type, $options)
            ->take($options->limit)
            ->orderByDesc('sequence')
            ->get()->reject(function ($entry) {
                return ! is_array($entry->content);
            })->map(function ($entry) {
                return new EntryResult(
                    $entry->uuid,
                    $entry->sequence,
                    $entry->batch_id,
                    $entry->type,
                    $entry->family_hash,
                    $entry->content,
                    $entry->created_at,
                    []
                );
            })->values();
    }

    /**
     * Counts the occurences of an exception.
     */
    protected function countExceptionOccurences(IncomingEntry $exception): int
    {
        return $this->table('telescope_entries')
                    ->where('type', EntryType::EXCEPTION)
                    ->where('family_hash', $exception->familyHash())
                    ->count();
    }

    /**
     * Store the given array of entries.
     */
    public function store(Collection $entries): void
    {
        if ($entries->isEmpty()) {
            return;
        }

        [$exceptions, $entries] = $entries->partition->isException();

        $this->storeExceptions($exceptions);

        $table = $this->table('telescope_entries');

        $entries->chunk($this->chunkSize)->each(function ($chunked) use ($table) {
            $table->insert($chunked->map(function ($entry) {
                $entry->content = json_encode($entry->content, JSON_INVALID_UTF8_SUBSTITUTE);

                return $entry->toArray();
            })->toArray());
        });

        $this->storeTags($entries->pluck('tags', 'uuid'));
    }

    /**
     * Store the given array of exception entries.
     */
    protected function storeExceptions(Collection $exceptions): void
    {
        $exceptions->chunk($this->chunkSize)->each(function ($chunked) {
            $this->table('telescope_entries')->insert($chunked->map(function ($exception) {
                $occurrences = $this->countExceptionOccurences($exception);

                $this->table('telescope_entries')
                        ->where('type', EntryType::EXCEPTION)
                        ->where('family_hash', $exception->familyHash())
                        ->update(['should_display_on_index' => false]);

                return array_merge($exception->toArray(), [
                    'family_hash' => $exception->familyHash(),
                    'content' => json_encode(array_merge(
                        $exception->content, ['occurrences' => $occurrences + 1]
                    )),
                ]);
            })->toArray());
        });

        $this->storeTags($exceptions->pluck('tags', 'uuid'));
    }

    /**
     * Store the tags for the given entries.
     */
    protected function storeTags(Collection $results): void
    {
        $results->chunk($this->chunkSize)->each(function ($chunked) {
            try {
                $this->table('telescope_entries_tags')->insert($chunked->flatMap(function ($tags, $uuid) {
                    return collect($tags)->map(function ($tag) use ($uuid) {
                        return [
                            'entry_uuid' => $uuid,
                            'tag' => $tag,
                        ];
                    });
                })->all());
            } catch (UniqueConstraintViolationException $e) {
                // Ignore tags that already exist...
            }
        });
    }

    /**
     * Store the given entry updates and return the failed updates.
     */
    public function update(Collection $updates): ?Collection
    {
        $failedUpdates = [];

        foreach ($updates as $update) {
            $entry = $this->table('telescope_entries')
                            ->where('uuid', $update->uuid)
                            ->where('type', $update->type)
                            ->first();

            if (! $entry) {
                $failedUpdates[] = $update;

                continue;
            }

            $content = json_encode(array_merge(
                json_decode($entry->content ?? $entry['content'] ?? [], true) ?: [], $update->changes
            ));

            $this->table('telescope_entries')
                            ->where('uuid', $update->uuid)
                            ->where('type', $update->type)
                            ->update(['content' => $content]);

            $this->updateTags($update);
        }

        return collect($failedUpdates);
    }

    /**
     * Update tags of the given entry.
     */
    protected function updateTags(EntryUpdate $entry): void
    {
        if (! empty($entry->tagsChanges['added'])) {
            try {
                $this->table('telescope_entries_tags')->insert(
                    collect($entry->tagsChanges['added'])->map(function ($tag) use ($entry) {
                        return [
                            'entry_uuid' => $entry->uuid,
                            'tag' => $tag,
                        ];
                    })->toArray()
                );
            } catch (UniqueConstraintViolationException $e) {
                // Ignore tags that already exist...
            }
        }

        collect($entry->tagsChanges['removed'])->each(function ($tag) use ($entry) {
            $this->table('telescope_entries_tags')->where([
                'entry_uuid' => $entry->uuid,
                'tag' => $tag,
            ])->delete();
        });
    }

    /**
     * Load the monitored tags from storage.
     */
    public function loadMonitoredTags(): void
    {
        try {
            $this->monitoredTags = $this->monitoring();
        } catch (\Throwable $e) {
            $this->monitoredTags = [];
        }
    }

    /**
     * Determine if any of the given tags are currently being monitored.
     */
    public function isMonitoring(array $tags): bool
    {
        if (is_null($this->monitoredTags)) {
            $this->loadMonitoredTags();
        }

        return count(array_intersect($tags, $this->monitoredTags)) > 0;
    }

    /**
     * Get the list of tags currently being monitored.
     */
    public function monitoring(): array
    {
        return $this->table('telescope_monitoring')->pluck('tag')->all();
    }

    /**
     * Begin monitoring the given list of tags.
     */
    public function monitor(array $tags): void
    {
        $tags = array_diff($tags, $this->monitoring());

        if (empty($tags)) {
            return;
        }

        $this->table('telescope_monitoring')
                    ->insert(collect($tags)
                    ->mapWithKeys(function ($tag) {
                        return ['tag' => $tag];
                    })->all());
    }

    /**
     * Stop monitoring the given list of tags.
     */
    public function stopMonitoring(array $tags): void
    {
        $this->table('telescope_monitoring')->whereIn('tag', $tags)->delete();
    }

    /**
     * Prune all of the entries older than the given date.
     */
    public function prune(DateTimeInterface $before, bool $keepExceptions): int
    {
        $query = $this->table('telescope_entries')
                ->where('created_at', '<', $before);

        if ($keepExceptions) {
            $query->where('type', '!=', 'exception');
        }

        $totalDeleted = 0;

        do {
            $deleted = $query->take($this->chunkSize)->delete();

            $totalDeleted += $deleted;
        } while ($deleted !== 0);

        return $totalDeleted;
    }

    /**
     * Clear all the entries.
     */
    public function clear(): void
    {
        do {
            $deleted = $this->table('telescope_entries')->take($this->chunkSize)->delete();
        } while ($deleted !== 0);

        do {
            $deleted = $this->table('telescope_monitoring')->take($this->chunkSize)->delete();
        } while ($deleted !== 0);
    }

    /**
     * Perform any clean-up tasks needed after storing Telescope entries.
     */
    public function terminate(): void
    {
        $this->monitoredTags = null;
    }

    /**
     * Get a query builder instance for the given table.
     */
    protected function table(string $table): Builder
    {
        return DB::connection($this->connection)->table($table);
    }
}
