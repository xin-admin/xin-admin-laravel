<?php

namespace App\Providers\AnnoRoute\Crud;

use App\Providers\AnnoRoute\BaseAttribute;
use App\Providers\AnnoRoute\CrudInterface;
use Attribute;
use Illuminate\Http\Request;

#[Attribute(Attribute::TARGET_CLASS)]
class Update extends BaseAttribute implements CrudInterface
{

    /** @var string 路由地址 */
    public string $route = '/{id}';

    /** @var string 请求方法 */
    public string $httpMethod = 'PUT';

    /** @var string|bool 权限字段 */
    public string|bool $authorize = 'update';

    /**
     * 修改请求
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
        return function (Request $request, int $id) use ($repository) {
            app($repository)->update($id, $request->all());
            return Update::success();
        };
    }
}