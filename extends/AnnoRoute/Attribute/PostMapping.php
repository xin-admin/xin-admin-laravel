<?php

namespace Xin\AnnoRoute\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class PostMapping
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