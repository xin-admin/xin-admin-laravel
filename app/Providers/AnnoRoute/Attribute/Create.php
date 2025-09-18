<?php

namespace App\Providers\AnnoRoute\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Create extends Mapping
{
    /**
     * 新增请求 Create Request
     *
     * @param string $handlerMethod 方法名称
     */
    public function __construct(
        public string $route = '',
        public string | array $middleware = '',
        public string $httpMethod = 'POST',
        public string $authorize = 'create',
        public string $handlerMethod = 'create'
    )
    {
    }
}