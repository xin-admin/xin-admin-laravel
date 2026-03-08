<?php

namespace App\Admin\Services;

use App\Common\Exceptions\RepositoryException;
use App\Common\Models\System\SysRuleModel;
use App\Common\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SysUserRuleService extends BaseService
{
    protected SysRuleModel $model;
    protected array $quickSearchField = ['name', 'key', 'path'];
    protected array $searchField = [
        'type' => '=',
        'status' => '=',
        'show' => '=',
        'parent_id' => '='
    ];

    protected function rules(): array
    {
        $type = request()->input('type');
        if (empty($type)) {
            throw new RepositoryException('权限类型为必填项！');
        }
        if (!in_array($type, ['menu', 'route', 'rule'])) {
            throw new RepositoryException('权限类型错误！');
        }
        $rules = [
            'parent_id' => [
                'required',
                'integer',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($value != 0 && !DB::table('sys_rule')->where('id', $value)->exists()) {
                        $fail('选择的上级部门不存在。');
                    }
                },
            ],
            'order' => 'required|integer',
            'name' => 'required',
            'key' => 'required|unique:sys_rule,key'
        ];
        if ($this->isUpdate()) {
            $rules['key'] = [
                'required',
                Rule::unique('sys_rule', 'key')->ignore(request()->route('id'))
            ];
        }
        if ($type == 'menu') {
            $rules += [
                'type' => 'required|string|in:menu',
                'local' => 'nullable|string',
                'icon' => 'nullable|string',
            ];
        } else if ($type == 'route') {
            $rules += [
                'type' => 'required|string|in:route',
                'path' => 'required|string',
                'local' => 'nullable|string',
                'icon' => 'nullable|string',
                'link' => 'required|integer|numeric|in:0,1',
            ];
        } else {
            $rules += [
                'type' => 'required|string|in:rule',
            ];
        }
        return $rules;
    }

    protected function messages(): array
    {
        return [
            'name.required' => '权限名称不能为空',
            'type.required' => '类型不能为空',
            'type.in' => '类型格式错误',
            'order.required' => '排序不能为空',
            'order.integer' => '排序必须为整数',
            'key.required' => '唯一标识不能为空',
            'key.unique' => '唯一标识已存在',
            'path.required' => '路径不能为空',
            'status.required' => '状态不能为空',
            'status.in' => '状态格式错误',
            'show.required' => '显示状态不能为空',
            'show.in' => '显示状态格式错误',
            'parent_id.required' => '父级权限不能为空',
            'parent_id.integer' => '父级权限格式错误',
            'parent_id.exists' => '父级权限不存在'
        ];
    }

    /**
     * 获取权限列表
     */
    public function getList(): JsonResponse
    {
        $rules = SysRuleModel::all();
        $data = $rules->toArray();
        $data = $this->getTreeData($data);
        return $this->success($data);
    }

    /**
     * 设置显示状态
     */
    public function setShow($ruleID): JsonResponse
    {
        $model = SysRuleModel::find($ruleID);
        if (! $model) {
            return $this->error(__('system.data_not_exist'));
        }
        $model->hidden = $model->hidden ? 0 : 1;
        $model->save();
        return $this->success();
    }

    /**
     * 设置状态
     */
    public function setStatus($ruleID): JsonResponse
    {
        $model = SysRuleModel::find($ruleID);
        if (! $model) {
            return $this->error(__('system.data_not_exist'));
        }
        $model->status = $model->status ? 0 : 1;
        $model->save();
        return $this->success();
    }

    /**
     * 获取父节点
     */
    public function getRuleParent(): JsonResponse
    {
        $data = SysRuleModel::query()
            ->whereIn('type', ['menu', 'route'])
            ->get(['name', 'id', 'parent_id'])
            ->toArray();
        $data = $this->getTreeData($data);
        return $this->success($data);
    }

    /** 获取权限选择项 */
    public function getRuleFields(): JsonResponse
    {
        $data = SysRuleModel::query()
            ->where("status", 1)
            ->get(['name as title', 'parent_id', 'id as key', 'id', 'local'])
            ->toArray();
        $data = $this->getTreeData($data);
        return $this->success($data);
    }
}
