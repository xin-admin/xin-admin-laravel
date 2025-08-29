<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SettingGroupRequest;
use App\Models\SettingGroupModel;
use App\Models\SettingModel;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Attribute\Create;
use Xin\AnnoRoute\Attribute\DeleteMapping;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\AnnoRoute\Attribute\Update;

/**
 * 设置分组控制器
 */
#[RequestMapping('/system/setting/group', 'system.setting.group')]
#[Query, Create, Update]
class SettingGroupController extends BaseController
{
    protected string $model = SettingGroupModel::class;
    protected string $formRequest = SettingGroupRequest::class;
    protected array $searchField = [
        'id' => '=',
        'key' => '=',
    ];

    /** 删除设置 */
    #[DeleteMapping(authorize: 'delete')]
    public function delete(): JsonResponse
    {
        $settingModel = new SettingModel;
        $data = request()->all();
        $delArr = explode(',', $data['id']);
        $setting = $settingModel->where('group_id', $delArr)->first();
        if ($setting) {
            return $this->error('请先删除该分组下的设置');
        }

        return $this->delete();
    }
}
