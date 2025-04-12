<?php

namespace Xin\Telescope;

class EntryUpdate
{
    /**
     * The entry's UUID.
     */
    public string $uuid;

    /**
     * The entry's type.
     */
    public string $type;

    /**
     * The properties that should be updated on the entry.
     */
    public array $changes = [];

    /**
     * The changes to be applied on the tags.
     */
    public array $tagsChanges = ['removed' => [], 'added' => []];

    /**
     * Create a new incoming entry instance.
     *
     * @param string $uuid
     * @param string $type
     * @param  array  $changes
     * @return void
     */
    public function __construct(string $uuid, string $type, array $changes)
    {
        $this->uuid = $uuid;
        $this->type = $type;
        $this->changes = $changes;
    }

    /**
     * Create a new entry update instance.
     *
     * @param  mixed  ...$arguments
     * @return static
     */
    public static function make(...$arguments): static
    {
        return new static(...$arguments);
    }

    /**
     * Set the properties that should be updated.
     *
     * @param  array  $changes
     * @return $this
     */
    public function change(array $changes): static
    {
        $this->changes = array_merge($this->changes, $changes);

        return $this;
    }

    /**
     * Add tags to the entry.
     *
     * @param  array  $tags
     * @return $this
     */
    public function addTags(array $tags): static
    {
        $this->tagsChanges['added'] = array_unique(
            array_merge($this->tagsChanges['added'], $tags)
        );

        return $this;
    }

    /**
     * Remove tags from the entry.
     *
     * @param  array  $tags
     * @return $this
     */
    public function removeTags(array $tags): static
    {
        $this->tagsChanges['removed'] = array_unique(
            array_merge($this->tagsChanges['removed'], $tags)
        );

        return $this;
    }
}
