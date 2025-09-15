<?php

namespace App\Repositories\Sys;

use App\Exceptions\RepositoryException;
use App\Models\Sys\SysSettingGroupModel;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;

class SysSettingGroupRepository extends Repository
{
    protected array $validation = [
        'key' => 'required',
        'title' => 'required',
        'remark' => 'sometimes|required',
    ];

    protected array $messages = [
        'key.required' => '键名字段是必填的',
        'title.required' => '标题字段是必填的',
        'remark.required' => '备注字段是必填的',
    ];

    protected array $searchField = [
        'id' => '=',
        'key' => '=',
    ];

    /**
     * @inheritDoc
     */
    protected function model(): Builder
    {
        return SysSettingGroupModel::query();
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