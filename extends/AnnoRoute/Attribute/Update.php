<?php

namespace Xin\AnnoRoute\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Update extends Mapping
{
    /**
     * 修改请求
     *
     * @param string $handlerMethod 方法名称 method
     */
    public function __construct(
        public string $route = '/{id}',
        public string | array $middleware = '',
        public string $httpMethod = 'PUT',
        public string $authorize = 'update',
        public string $handlerMethod = 'update'
    )
    {
    }
}