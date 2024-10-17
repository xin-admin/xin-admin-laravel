<?php

namespace App\Http\Controllers\Admin\User;

use App\Attribute\Auth;
use App\Http\Controllers\Admin\Controller;
use App\Models\User\UserRuleModel;
use Illuminate\Http\JsonResponse;

class UserRuleController extends Controller
{

    protected array $searchField = [
        'id' => '=',
        'pid' => '=',
        'type' => '=',
        'name' => 'like',
        'key' => '=',
        'create_time' => 'date',
        'update_time' => 'date'
    ];

    protected array $rule = [
        'pid' => 'required|integer',
        'name' => 'required|string|max:50',
        'type' => 'required|integer|in:0,1,2',
        'key' => 'required|string|max:50',
        'show' => 'required|integer|between:0,1',
        'status' => 'required|integer|between:0,1',
        'sort' => 'required|integer',
        'path' => 'nullable|string|max:50',
        'icon' => 'nullable|string|max:255',
        'locale' => 'nullable|string|max:255',
    ];


    protected string $authName = 'user.rule';

    protected string $model = UserRuleModel::class;

    #[Auth('list')]
    public function list(): JsonResponse
    {
        $rootNode = $this->model::query()
            ->orderBy('sort', 'desc')
            ->get()
            ->toArray();
        $data = $this->getTreeData($rootNode);
        return $this->success(compact('data'));
    }

    /**
     * 获取菜单节点
     */
    #[Auth('list')]
    public function getRulePid(): JsonResponse
    {
        $rootNode = $this->model::query()
            ->where('type', '<>', '2')
            ->orderBy('sort', 'desc')
            ->get()
            ->toArray();
        $data = $this->getTreeData($rootNode);
        return $this->success(compact('data'));
    }
}
