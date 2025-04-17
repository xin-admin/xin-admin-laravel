<?php

namespace Xin\Telescope\Storage;

use Illuminate\Http\Request;

class EntryQueryOptions
{
    /**
     * The number of entries to retrieve.
     *
     * @var int
     */
    public int $limit = 10;

    /**
     * The page of entries to retrieve.
     * @var int
     */
    public int $page = 1;

    /**
     * The tag that must belong to retrieved entries
     * @var string
     */
    public string $type;

    /**
     * The date of entries to retrieve
     */
    public string $date;

    /**
     * Create new entry query options from the incoming request.
     *
     * @param Request $request
     * @return static
     */
    public static function fromRequest(Request $request): static
    {
        // 表单验证必须包含 type
        $params = $request->validate([
            'type' => ['required', 'string'],
            'date' => ['nullable', 'date'],
            'pageSize' => ['nullable', 'integer'],
            'current' => ['nullable', 'integer'],
        ]);

        return (new static)
            ->type($params['type'])
            ->date($params['date'] ?? date('Y-m-d'))
            ->page($params['current'] ?? 1)
            ->limit($params['pageSize'] ?? 10);
    }

    /**
     * Set the date of entries to retrieve.
     */
    public function date(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Set the type of entries to retrieve.
     */
    public function page(int $page): static
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Set the type of entries to retrieve.
     */
    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set the number of entries that should be retrieved.
     */
    public function limit(int $limit): static
    {
        $this->limit = $limit;

        return $this;
    }
}
