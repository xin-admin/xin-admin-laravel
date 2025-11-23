<?php

namespace App\Repositories\Sys;

use App\Exceptions\RepositoryException;
use App\Models\Sys\SysDictModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

class SysDictRepository extends BaseRepository
{

    /** @var array|string[] 快速搜索字段 */
    protected array $quickSearchField = ['name', 'code', 'describe'];

    /**
     * @inheritDoc
     */
    protected function model(): Builder
    {
        return SysDictModel::query();
    }

    protected function rules(): array
    {
        if($this->isUpdate()) {
            $id = request()->route('id');
            return [
                'name' => 'required',
                'code' => [
                    'required',
                    Rule::unique('sys_dict', 'code')->ignore($id)
                ],
                'describe' => 'sometimes',
                'type' => 'required|in:default,badge,tag'
            ];
        } else {
            return [
                'name' => 'required',
                'code' => 'required|unique:sys_dict,code',
                'describe' => 'sometimes',
                'type' => 'required|in:default,badge,tag'
            ];
        }
    }

    protected function messages(): array
    {
        return [
            'name.required' => '字典名称不能为空',
            'code.required' => '字典编码不能为空',
            'code.unique' => '字典编码已存在',
            'type.required' => '字典类型不能为空',
            'type.in' => '字典类型格式错误'
        ];
    }

    public function delete(int $id): bool
    {
        $model = $this->model()->find($id);
        $count = $model->dictItems()->count();
        if ($count > 0) {
            throw new RepositoryException("字典包含子项！");
        }
        return $model->delete();
    }
}