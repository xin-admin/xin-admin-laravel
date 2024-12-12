<?php

namespace App\Providers;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * 重写分页返回数组
 * Class LengthAwarePaginatorService
 */
class LengthAwarePaginatorService extends LengthAwarePaginator
{
    public function toArray(): array
    {
        return [
            'data' => $this->items(),
            'total' => $this->total(),
            'pageSize' => $this->perPage(),
            'current' => $this->currentPage(),
        ];
    }
}
