<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

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
