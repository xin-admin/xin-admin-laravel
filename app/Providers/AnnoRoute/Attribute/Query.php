<?php

namespace App\Providers\AnnoRoute\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Query extends Mapping
{
    /**
     * 查询列表请求
     *
     * @param string $handlerMethod 方法名称 method
     */
    public function __construct(
        public string $route = '',
        public string | array $middleware = '',
        public string $httpMethod = 'GET',
        public string $authorize = 'query',
        public string $handlerMethod = 'query'
    )
    {
    }
}