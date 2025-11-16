<?php

namespace App\Repositories\Sys;

use App\Exceptions\RepositoryException;
use App\Models\Sys\SysFileGroupModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SysFileGroupRepository extends BaseRepository
{

    protected array $searchField = ['name' => 'like'];

    protected function model(): Builder
    {
        return SysFileGroupModel::query();
    }

    protected function rules(): array
    {
        if(! $this->isUpdate()) {
            return [
                'parent_id' => [
                    'required',
                    'integer',
                    function ($attribute, $value, $fail) {
                        if ($value != 0 && !DB::table('sys_dept')->where('id', $value)->exists()) {
                            $fail('选择的上级部门不存在。');
                        }
                    },
                ],
                'name' => 'required|string|max:255',
                'describe' => 'sometimes|string|max:500',
                'sort' => 'sometimes|integer|min:0',
            ];
        } else {
            return [
                'name' => 'required|string|max:255',
                'describe' => 'sometimes|string|max:500',
                'sort' => 'sometimes|integer|min:0',
            ];
        }
    }

    protected function messages(): array
    {
        return [
            'name.required' => '分组名称不能为空',
            'name.string' => '分组名称必须是字符串',
            'name.max' => '分组名称不能超过50个字符',

            'sort.integer' => '分组排序必须是整数',
            'sort.min' => '分组排序不能为负数',

            'describe.string' => '分组描述必须是字符串',
            'describe.max' => '分组描述不能超过500个字符',
        ];
    }

    public function delete(int $id): bool
    {
        $model = SysFileGroupModel::find($id);
        if (empty($model)) {
            throw new RepositoryException('Model not found');
        }
        if ($model->countFiles > 0) {
            throw new RepositoryException('该文件夹下存在文件，无法删除');
        }
        return $model->delete();
    }
}