<?php

namespace App\Services\System;

use App\Models\System\SysDeptModel;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SysUserDeptService extends BaseService
{
    protected SysDeptModel $model;
    protected array $quickSearchField = ['name', 'leader', 'phone'];
    protected array $searchField = [
        'parent_id' => '=',
        'status' => '='
    ];

    protected function rules(): array
    {
        if (! $this->isUpdate()) {
            return [
                'name' => 'required|unique:sys_dept,name',
                'code' => 'required|unique:sys_dept,code',
                'type' => 'required|integer|in:0,1,2',
                'parent_id' => [
                    'required',
                    'integer',
                    function ($attribute, $value, $fail) {
                        if ($value != 0 && !DB::table('sys_dept')->where('id', $value)->exists()) {
                            $fail('选择的上级部门不存在。');
                        }
                    },
                ],
                'sort' => 'required|integer',
                'phone' => 'nullable',
                'address' => 'nullable',
                'email' => 'nullable|email',
                'status' => 'required|in:0,1',
                'remark' => 'nullable',
            ];
        } else {
            $id = request()->route('id');
            return [
                'name' => [
                    'required',
                    Rule::unique('sys_dept', 'name')->ignore($id)
                ],
                'code' => [
                    'required',
                    Rule::unique('sys_dept', 'code')->ignore($id)
                ],
                'type' => 'required|integer|in:0,1,2',
                'sort' => 'required|integer',
                'phone' => 'nullable',
                'address' => 'nullable',
                'email' => 'nullable|email',
                'status' => 'required|in:0,1',
                'remark' => 'nullable',
            ];
        }
    }

    protected function messages(): array
    {
        return [
            'name.required' => '部门名称不能为空',
            'name.unique' => '部门名称已存在',
            'code.required' => '部门编码不能为空',
            'code.unique' => '部门编码已存在',
            'type.required' => '部门类型不能为空',
            'type.integer' => '部门类型必须是整数',
            'type.in' => '部门类型错误',
            'parent_id.required' => '上级部门不能为空',
            'parent_id.integer' => '上级部门ID必须是整数',
            'parent_id.exists' => '选择的上级部门不存在',
            'sort.required' => '排序字段不能为空',
            'sort.integer' => '排序字段必须是整数',
            'email.email' => '请输入有效的邮箱地址',
            'status.required' => '状态不能为空',
            'status.in' => '状态类型错误',
        ];
    }

    /**
     * 获取部门树状列表
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $data = SysDeptModel::orderBy('sort', 'desc')->get()->toArray();
        $data = $this->getTreeData($data);

        return $this->success($data);
    }

    /**
     * 批量删除部门
     * @param Request $request
     * @return JsonResponse
     */
    public function batchDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:sys_dept,id'
        ]);

        $ids = $request->input('ids');

        // 检查是否有下级部门
        $departmentsWithChildren = SysDeptModel::whereIn('id', $ids)
            ->whereHas('children')
            ->get();

        if ($departmentsWithChildren->isNotEmpty()) {
            return $this->error('存在下级部门的部门无法删除');
        }

        // 执行删除
        SysDeptModel::whereIn('id', $ids)->delete();

        return $this->success('部门删除成功');
    }

    /**
     * 获取部门用户列表
     * @param int $id
     * @return JsonResponse
     */
    public function users(int $id): JsonResponse
    {
        $model = SysDeptModel::query()->find($id);
        if (empty($model)) {
            return $this->error('部门不存在');
        }
        $pageSize = request()->input('pageSize') ?? 10;
        $data = $model->users()
            ->select(['id', 'username', 'nickname', 'email', 'mobile', 'status'])
            ->paginate($pageSize)
            ->toArray();
        return $this->success($data);
    }


    /** 获取部门选择项 */
    public function getDeptField(): array
    {
        $field = SysDeptModel::where('status', 0)
            ->select(['id as dept_id', 'name', 'parent_id'])
            ->get()
            ->toArray();
        return $this->buildTree($field);
    }

    /** 构建树形结构 */
    private function buildTree(array $items, $parentId = 0): array
    {
        $tree = [];
        foreach ($items as $item) {
            if ($item['parent_id'] == $parentId) {
                $children = $this->buildTree($items, $item['dept_id']);
                $node = [
                    'dept_id' => $item['dept_id'],
                    'name' => $item['name'],
                    'children' => $children
                ];
                $tree[] = $node;
            }
        }
        return $tree;
    }
}
