<?php

namespace App\Providers\AnnoRoute\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Delete extends Mapping
{
    /**
     * 删除请求
     *
     * @param string $handlerMethod 方法名称 method
     */
    public function __construct(
        public string $route = '/{id}',
        public string | array $middleware = '',
        public string $httpMethod = 'DELETE',
        public string $authorize = 'delete',
        public string $handlerMethod = 'delete'
    )
    {
    }
}