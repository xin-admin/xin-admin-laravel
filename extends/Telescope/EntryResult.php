<?php

namespace Xin\Telescope;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use JsonSerializable;

class EntryResult implements JsonSerializable
{
    /**
     * The entry's primary key.
     */
    public mixed $id;

    /**
     * The entry's sequence.
     */
    public mixed $sequence;

    /**
     * The entry's batch ID.
     */
    public string $batchId;

    /**
     * The entry's type.
     */
    public string $type;

    /**
     * The entry's family hash.
     */
    public ?string $familyHash;

    /**
     * The entry's content.
     */
    public array $content = [];

    /**
     * The datetime that the entry was recorded.
     */
    public CarbonInterface|Carbon $createdAt;

    /**
     * The tags assigned to the entry.
     */
    private array $tags;

    /**
     * Create a new entry result instance.
     *
     * @param  mixed  $id
     * @param  mixed  $sequence
     * @param  string  $batchId
     * @param  string  $type
     * @param  string|null  $familyHash
     * @param  array  $content
     * @param  CarbonInterface|Carbon  $createdAt
     * @param  array  $tags
     */
    public function __construct(mixed $id, mixed $sequence, string $batchId, string $type, ?string $familyHash, array $content, $createdAt, $tags = [])
    {
        $this->id = $id;
        $this->type = $type;
        $this->tags = $tags;
        $this->batchId = $batchId;
        $this->content = $content;
        $this->sequence = $sequence;
        $this->createdAt = $createdAt;
        $this->familyHash = $familyHash;
    }

    /**
     * Get the array representation of the entry.
     *
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return collect([
            'id' => $this->id,
            'sequence' => $this->sequence,
            'batch_id' => $this->batchId,
            'type' => $this->type,
            'content' => $this->content,
            'tags' => $this->tags,
            'family_hash' => $this->familyHash,
            'created_at' => $this->createdAt->toDateTimeString(),
        ])->all();
    }
}
