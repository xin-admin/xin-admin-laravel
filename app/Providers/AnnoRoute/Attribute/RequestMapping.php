<?php

namespace App\Providers\AnnoRoute\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class RequestMapping
{
    /**
     * @param string $routePrefix 路由
     * @param string $abilitiesPrefix 权限前缀
     * @param string | array $middleware 中间件
     * @param string | null $authGuard 用户提供程序
     */
    public function __construct(
        public string $routePrefix = '',
        public string $abilitiesPrefix = '',
        public string | array $middleware = '',
        public ?string $authGuard = null
    )
    {
    }
}