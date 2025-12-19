<?php

namespace App\Providers\AnnoRoute\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Create extends Mapping
{
    /**
     * 新增请求 Create Request
     *
     * @param string $route 路由地址
     * @param string|array $middleware 中间件
     * @param string $httpMethod HTTP方法
     * @param string $authorize 权限标识
     * @param string $handlerMethod 方法名称
     * @param array $where 路由参数约束
     */
    public function __construct(
        public string $route = '',
        public string | array $middleware = '',
        public string $httpMethod = 'POST',
        public string $authorize = 'create',
        public string $handlerMethod = 'create',
        public array $where = []
    )
    {
    }
}