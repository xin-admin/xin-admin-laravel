<?php

namespace App\Providers\AnnoRoute\Crud;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Find extends BaseCRUD
{

    /** @var string 路由地址 */
    public string $route = '/{id}';

    /** @var string 请求方法 */
    public string $httpMethod = 'GET';

    /** @var string|bool 权限字段 */
    public string|bool $authorize = 'find';

    /**
     * 查询对象请求
     * @param string|array $middleware 中间件
     * @param array $where 路由参数约束
     */
    public function __construct(
        public string | array $middleware = '',
        public array $where = ['id' => '[0-9]+']
    )
    {
    }

    public function store(string $repository): \Closure
    {
        return function (int $id) use ($repository) {
            $item = app($repository)->find($id);
            return Find::success($item);
        };
    }

}