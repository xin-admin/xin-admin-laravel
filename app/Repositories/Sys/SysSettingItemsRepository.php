<?php

namespace App\Repositories\Sys;

use App\Exceptions\RepositoryException;
use App\Models\Sys\SysSettingItemsModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

class SysSettingItemsRepository extends BaseRepository
{

    protected array $searchField = [ 'group_id' => '=' ];

    protected function rules(): array
    {
        return [
            'title' => 'required',
            'key' => 'required|min:2|max:255',
            'group_id' => 'required|exists:sys_setting_group,id',
            'type' => 'required',
            'describe' => 'sometimes|string',
            'options' => [
                'sometimes',
                'nullable',
                'string',
                'regex:/^(?:[^=\n]+=[^=\n]+)(?:\n[^=\n]+=[^=\n]+)*$/',
            ],
            'props' => [
                'sometimes',
                'nullable',
                'string',
                'regex:/^(?:[^=\n]+=[^=\n]+)(?:\n[^=\n]+=[^=\n]+)*$/',
            ],
            'sort' => 'sometimes|integer',
            'values' => 'sometimes|string',
        ];
    }

    protected function messages(): array
    {
        return [
            'title.required' => '标题字段是必填的',
            'key.required' => '键名字段是必填的',
            'key.min' => '键名至少需要 :min 个字符',
            'key.max' => '键名不能超过 :max 个字符',
            'group_id.required' => '分组ID是必填的',
            'group_id.exists' => '选择的分组不存在',
            'type.required' => '类型字段是必填的',
            'describe.string' => '描述必须是字符串',
            'options.regex' => '选项格式不正确，应为 key=value 格式，多个用换行分隔',
            'props.regex' => '属性格式不正确，应为 key=value 格式，多个用换行分隔',
            'sort.integer' => '排序必须是整数',
            'values.string' => '值必须是字符串',
        ];
    }

    /** 验证数据 */
    protected function validation(array $data): array
    {
        $data = parent::validation($data);
        $num = $this->model()->where('group_id', $data['group_id'])->where('key', $data['key'])->count();
        if($num > 0 && request()->method() != 'PUT') {
            throw new RepositoryException(
                'Validation failed: ' . '该键名在此分组中已存在',
            );
        }
        return $data;
    }

    /**
     * @inheritDoc
     */
    protected function model(): Builder
    {
        return SysSettingItemsModel::query();
    }

    public function list(array $params): array
    {
        if(empty($params['group_id'])) {
            throw new RepositoryException('请选择设置分组');
        }
        return $this->model()
            ->where('group_id', $params['group_id'])
            ->get()
            ->toArray();
    }
}