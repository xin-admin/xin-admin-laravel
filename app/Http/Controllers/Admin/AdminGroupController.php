<?php

namespace App\Http\Controllers\Admin;

use App\Attribute\Auth;
use App\Attribute\Monitor;
use App\Models\Admin\AdminGroupModel;
use Illuminate\Http\JsonResponse;

class AdminGroupController extends Controller
{
    protected string $authName = 'admin.group';

    protected string $model = AdminGroupModel::class;

    protected array $searchField = [
        'id' => '=',
        'name' => 'like',
        'pid' => '=',
        'create_time' => 'date',
        'update_time' => 'date',
    ];

    protected array $rule = [
        'name' => 'required|string',
        'pid' => 'required|integer',
    ];

    /**
     * 查询
     */
    #[Auth('list')]
    public function list(): JsonResponse
    {
        $model = new $this->model;
        $rootNode = $model->get()->toArray();
        $data = $this->getTreeData($rootNode);
        return $this->success(compact('data'));
    }

    /**
     * 设置分组权限
     */
    #[Auth, Monitor('设置分组权限')]
    public function setGroupRule(): JsonResponse
    {
        $params = request()->post();
        if (! isset($params['id'])) {
            return $this->warn('请选择管理分组');
        }
        $group = $this->model::query()->find($params['id']);
        if (! $group) {
            return $this->warn('用户组不存在');
        }
        $group->rules = implode(',', $params['rule_ids']);
        $group->save();

        return $this->success();
    }
}
