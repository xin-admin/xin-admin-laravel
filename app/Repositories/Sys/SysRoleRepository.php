<?php

namespace App\Repositories\Sys;

use App\Exceptions\RepositoryException;
use App\Models\Sys\SysRoleModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

class SysRoleRepository extends BaseRepository
{

    /** @var array|string[] 搜索字段 */
    protected array $searchField = [
        'status' => '=',
        'name' => 'like',
    ];

    /** @var array|string[] 快速搜索字段 */
    protected array $quickSearchField = ['name', 'description'];

    /**
     * @inheritDoc
     */
    protected function model(): Builder
    {
        return SysRoleModel::query();
    }

    protected function rules(): array
    {
        if(! $this->isUpdate()) {
            return [
                'name' => 'required|unique:sys_role,name',
                'sort' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'status' => 'required|integer|in:0,1'
            ];
        } else {
            $id = request()->route('id');
            return [
                'name' => [
                    'required',
                    'string',
                    Rule::unique('sys_role', 'name')->ignore($id)
                ],
                'sort' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'status' => 'required|integer|in:0,1'
            ];
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => '角色名称不能为空',
            'name.unique' => '角色名称已存在',
            'sort.required' => '排序不能为空',
            'sort.integer' => '排序必须为整数',
            'status.required' => '状态不能为空',
            'status.in' => '状态格式错误'
        ];
    }

    public function delete(int $id): bool
    {
        $model = SysRoleModel::find($id);
        if (empty($model)) {
            throw new RepositoryException('Model not found');
        }
        if ($model->countUser > 0) {
            throw new RepositoryException('该角色下存在用户，无法删除');
        }
        return $model->delete();
    }
}