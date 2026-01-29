<?php

namespace App\Providers\AnnoRoute\Route;

use App\Providers\AnnoRoute\BaseAttribute;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class PostRoute extends BaseAttribute
{
    /** @var string 请求方法 */
    public string $httpMethod = 'POST';

    /**
     * @param string $route 路由地址
     * @param string|bool $authorize 权限字段
     * @param string|array $middleware 中间件
     * @param array $where 路由参数约束
     */
    public function __construct(
        public string         $route = '',
        public string|bool    $authorize = true,
        public string | array $middleware = '',
        public array          $where = [],
    )
    {
    }
}