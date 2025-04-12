<?php

namespace Xin\Telescope\Storage;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'telescope_entries';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = null;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'content' => 'json',
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Prevent Eloquent from overriding uuid with `lastInsertId`.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Scope the query for the given query options.
     */
    #[Scope]
    public function withTelescopeOptions(Builder $query, string $type, EntryQueryOptions $options): Builder
    {
        $this->whereType($query, $type)
                ->whereBatchId($query, $options)
                ->whereTag($query, $options)
                ->whereFamilyHash($query, $options)
                ->whereBeforeSequence($query, $options)
                ->filter($query, $options);

        return $query;
    }

    /**
     * Scope the query for the given type.
     */
    protected function whereType(Builder $query, string $type): static
    {
        $query->when($type, function ($query, $type) {
            return $query->where('type', $type);
        });

        return $this;
    }

    /**
     * Scope the query for the given batch ID.
     */
    protected function whereBatchId(Builder $query, EntryQueryOptions $options): static
    {
        $query->when($options->batchId, function ($query, $batchId) {
            return $query->where('batch_id', $batchId);
        });

        return $this;
    }

    /**
     * Scope the query for the given type.
     */
    protected function whereTag(Builder $query, EntryQueryOptions $options): static
    {
        $query->when($options->tag, function ($query, $tag) {
            $tags = collect(explode(',', $tag))->map(fn ($tag) => trim($tag));

            if ($tags->isEmpty()) {
                return $query;
            }

            return $query->whereIn('uuid', function ($query) use ($tags) {
                $query->select('entry_uuid')->from('telescope_entries_tags')
                    ->whereIn('entry_uuid', function ($query) use ($tags) {
                        $query->select('entry_uuid')->from('telescope_entries_tags')->whereIn('tag', $tags->all());
                    });
            });
        });

        return $this;
    }

    /**
     * Scope the query for the given type.
     */
    protected function whereFamilyHash(Builder $query, EntryQueryOptions $options): static
    {
        $query->when($options->familyHash, function ($query, $hash) {
            return $query->where('family_hash', $hash);
        });

        return $this;
    }

    /**
     * Scope the query for the given pagination options.
     */
    protected function whereBeforeSequence(Builder $query, EntryQueryOptions $options): static
    {
        $query->when($options->beforeSequence, function ($query, $beforeSequence) {
            return $query->where('sequence', '<', $beforeSequence);
        });

        return $this;
    }

    /**
     * Scope the query for the given display options.
     */
    protected function filter(Builder $query, EntryQueryOptions $options): static
    {
        if ($options->familyHash || $options->tag || $options->batchId) {
            return $this;
        }

        $query->where('should_display_on_index', true);

        return $this;
    }

    /**
     * Get the current connection name for the model.
     */
    public function getConnectionName(): string
    {
        return config('telescope.storage.database.connection');
    }
}
