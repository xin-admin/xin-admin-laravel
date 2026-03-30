<?php

namespace Xin\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\ServiceProvider;

class PaginationProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('Illuminate\Pagination\LengthAwarePaginator', function ($app, $options) {
            return new class(
                $options['items'],
                $options['total'],
                $options['perPage'],
                $options['currentPage'],
                $options['options']
            ) extends LengthAwarePaginator {
                public function toArray(): array
                {
                    return [
                        'data' => $this->items(),
                        'total' => $this->total(),
                        'pageSize' => $this->perPage(),
                        'current' => $this->currentPage(),
                    ];
                }
            };
        });
    }
}
