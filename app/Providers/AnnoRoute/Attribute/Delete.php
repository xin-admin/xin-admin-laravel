<?php

namespace App\Providers\AnnoRoute\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Delete extends Mapping
{
    /**
     * 删除请求
     *
     * @param string $route 路由地址
     * @param string|array $middleware 中间件
     * @param string $httpMethod HTTP方法
     * @param string $authorize 权限标识
     * @param string $handlerMethod 方法名称
     * @param array $where 路由参数约束
     */
    public function __construct(
        public string $route = '/{id}',
        public string | array $middleware = '',
        public string $httpMethod = 'DELETE',
        public string $authorize = 'delete',
        public string $handlerMethod = 'delete',
        public array $where = ['id' => '[0-9]+']
    )
    {
    }
}