<?php

namespace App\Attribute\route;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class GetMapping
{
    /**
     * @param string $route 路由
     * @param string $middleware 中间件
     */
    public function __construct(
        public string $route = '',
        public string $middleware = '',
    )
    {
    }
}