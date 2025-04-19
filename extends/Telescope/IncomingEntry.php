<?php

namespace Xin\Telescope;

use DateTimeInterface;
use Illuminate\Contracts\Auth\Authenticatable;

class IncomingEntry
{
    /**
     * The entry's type.
     */
    public string $type;

    /**
     * The currently authenticated user, if applicable.
     */
    public mixed $user;

    /**
     * The entry's content.
     */
    public array $content = [];

    /**
     * Create a new incoming entry instance.
     */
    public function __construct(array $content, string $type)
    {
        $this->type = $type;

        $this->content = array_merge($content, [
            'host_name' => gethostname(),
            'recorded_at' => now()->toDateTimeString()
        ]);
    }

    /**
     * Create a new entry instance.
     */
    public static function make(array $content, string $type): static
    {
        return new static($content, $type);
    }

    /**
     * Set the currently authenticated user.
     */
    public function user(Authenticatable $user): static
    {
        $this->user = $user;

        $this->content = array_merge($this->content, [
            'user' => [
                'id' => $user->getAuthIdentifier(),
                'name' => $user->username ?? null,
                'email' => $user->email ?? null,
                'avatar' => $user->avatarUrl ?? null,
            ],
        ]);

        return $this;
    }

    /**
     * Set the entry's type.
     */
    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Determine if the incoming entry is a cache entry.
     */
    public function isCache(): bool
    {
        return $this->type === EntryType::CACHE;
    }

    /**
     * Determine if the incoming entry is a auth.
     */
    public function isAuth(): bool
    {
        return $this->type === EntryType::AUTH;
    }

    /**
     * Determine if the incoming entry is a query.
     */
    public function isQuery(): bool
    {
        return $this->type === EntryType::QUERY;
    }

    /**
     * Determine if the incoming entry is a slow query.
     */
    public function isSlowQuery(): bool
    {
        return $this->type === EntryType::QUERY && ($this->content['slow'] ?? false);
    }

    /**
     * Determine if the incoming entry is a redis.
     */
    public function isRedis(): bool
    {
        return $this->type === EntryType::REDIS;
    }

    /**
     * Determine if the incoming entry is a request.
     */
    public function isRequest(): bool
    {
        return $this->type === EntryType::REQUEST;
    }

    /**
     * Determine if the incoming entry is a failed request.
     */
    public function isFailedRequest(): bool
    {
        return $this->type === EntryType::REQUEST &&
            ($this->content['response_status'] ?? 200) >= 500;
    }

    /**
     * Get the entry's content as a string.
     */
    public function getContentAsString(): string
    {
        return json_encode($this->content, JSON_INVALID_UTF8_SUBSTITUTE);
    }
}
