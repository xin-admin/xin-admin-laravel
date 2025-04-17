<?php

namespace Xin\Telescope;

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
    public string $createdAt;

    /**
     * Create a new entry result instance.
     *
     * @param mixed $id
     * @param mixed $sequence
     * @param string $batchId
     * @param string $type
     * @param string|null $familyHash
     * @param array $content
     * @param string $createdAt
     */
    public function __construct(mixed $id, mixed $sequence, string $batchId, string $type, ?string $familyHash, array $content, string $createdAt)
    {
        $this->id = $id;
        $this->type = $type;
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
            'family_hash' => $this->familyHash,
            'created_at' => $this->createdAt,
        ])->all();
    }
}
