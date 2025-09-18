<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use Illuminate\Http\JsonResponse;

#[RequestMapping('/admin')]
class IndexController extends BaseController
{
    protected array $noPermission = ['index'];

    /** 测试接口 */
    #[GetMapping]
    public function index(): JsonResponse
    {
        $webSetting = setting('web');
        return $this->success(compact('webSetting'), '恭喜你已经成功安装 Xin Admin');
    }
}
