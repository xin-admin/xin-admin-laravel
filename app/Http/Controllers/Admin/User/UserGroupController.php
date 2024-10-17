<?php

namespace App\Http\Controllers\Admin\User;

use App\Attribute\Auth;
use App\Http\Controllers\Admin\Controller;
use App\Models\User\UserGroupModel;
use Illuminate\Http\JsonResponse;

class UserGroupController extends Controller
{
    protected string $model = UserGroupModel::class;

    protected string $authName = 'user.group';

    protected array $searchField = [
        'id' => '=',
        'name' => 'like',
        'pid' => '=',
        'create_time' => 'date',
        'update_time' => 'date',
    ];

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
    #[Auth('setGroupRule')]
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
