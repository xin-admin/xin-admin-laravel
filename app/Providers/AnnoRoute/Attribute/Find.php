<?php

namespace App\Providers\AnnoRoute\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Find extends Mapping
{
    /**
     * 查询单条数据请求
     *
     * @param string $handlerMethod 方法名称 method
     */
    public function __construct(
        public string $route = '/{id}',
        public string | array $middleware = '',
        public string $httpMethod = 'GET',
        public string $authorize = 'find',
        public string $handlerMethod = 'find'
    )
    {
    }
}