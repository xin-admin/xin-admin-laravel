<?php

namespace App\Repositories\Sys;

use App\Exceptions\RepositoryException;
use App\Models\Sys\SysSettingGroupModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

class SysSettingGroupRepository extends BaseRepository
{

    /**
     * @inheritDoc
     */
    protected function model(): Builder
    {
        return SysSettingGroupModel::query();
    }

    protected function rules(): array
    {
       return [
           'key' => 'required',
           'title' => 'required',
           'remark' => 'sometimes|required',
       ];
    }

    protected function messages(): array
    {
        return [
            'key.required' => '键名字段是必填的',
            'title.required' => '标题字段是必填的',
            'remark.required' => '备注字段是必填的',
        ];
    }

    public function list(array $params): array
    {
        if(empty($params['keywordSearch'])){
            return $this->model()
                ->get()
                ->toArray();
        }else {
            return $this->model()
                ->whereAny(
                    ['title', 'remark', 'key'],
                    'like',
                    '%'.str_replace('%', '\%', $params['keywordSearch']).'%'
                )
                ->get()
                ->toArray();
        }
    }

    public function delete(int $id): bool
    {
        $model = $this->model()->find($id);
        if (empty($model)) {
            throw new RepositoryException('Model not found');
        }
        $count = $model->settings()->count();
        if ($count > 0) {
            throw new RepositoryException('当前分组有未删除的设置项！');
        }
        return $model->delete();
    }

}