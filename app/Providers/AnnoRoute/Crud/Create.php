<?php

namespace App\Providers\AnnoRoute\Crud;

use Attribute;
use Illuminate\Http\Request;

#[Attribute(Attribute::TARGET_CLASS)]
class Create extends BaseCRUD
{
    /** @var string 路由地址 */
    public string $route = '';

    /** @var string 请求方法 */
    public string $httpMethod = 'POST';

    /** @var string|bool 权限字段 */
    public string|bool $authorize = 'create';

    /**
     * 新增请求
     * @param string|array $middleware 中间件
     * @param array $where 路由参数约束
     */
    public function __construct(
        public string | array $middleware = '',
        public array $where = []
    )
    {
    }

    public function store(string $repository): \Closure
    {
        return function (Request $request) use ($repository) {
            app($repository)->create($request->all());
            return Create::success();
        };
    }
}