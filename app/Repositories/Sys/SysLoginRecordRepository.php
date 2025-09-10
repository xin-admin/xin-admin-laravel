<?php

namespace App\Repositories\Sys;

use App\Models\Sys\SysLoginRecordModel;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;

class SysLoginRecordRepository extends Repository
{
    /** @var array|string[] 验证规则 */
    protected array $validation = [
        'username' => 'required',
        'user_id' => 'required|exists:sys_user,id',
        'ipaddr' => 'required|ip',
        'login_location' => 'required',
        'browser' => 'required',
        'os' => 'required',
        'status' => 'required|in:0,1',
        'msg' => 'required',
        'login_time' => 'required|date'
    ];

    /** @var array 验证消息 */
    protected array $messages = [
        'username.required' => '用户名不能为空',
        'user_id.required' => '用户ID不能为空',
        'user_id.exists' => '用户不存在',
        'ipaddr.required' => 'IP地址不能为空',
        'ipaddr.ip' => 'IP地址格式错误',
        'login_location.required' => '登录地点不能为空',
        'browser.required' => '浏览器不能为空',
        'os.required' => '操作系统不能为空',
        'status.required' => '登录状态不能为空',
        'status.in' => '登录状态格式错误',
        'msg.required' => '提示消息不能为空',
        'login_time.required' => '登录时间不能为空',
        'login_time.date' => '登录时间格式错误'
    ];

    /** @var array|string[] 搜索字段 */
    protected array $searchField = [
        'status' => '=',
        'user_id' => '='
    ];

    /** @var array|string[] 快速搜索字段 */
    protected array $quickSearchField = ['username', 'ipaddr', 'browser', 'os'];

    /**
     * @inheritDoc
     */
    protected function model(): Builder
    {
        return SysLoginRecordModel::query();
    }

    /**
     * 通过用户ID获取记录
     */
    public function getRecordByID(int $id): array
    {
        return $this->model()
            ->where('user_id', $id)
            ->limit(20)
            ->orderBy('log_id', 'desc')
            ->get()
            ->toArray();
    }
}