<?php

namespace App\Http\Controllers\Admin;

use App\Trait\RequestJson;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\RequestAttribute;
use Xin\AnnoRoute\Route\GetRoute;

#[RequestAttribute]
class IndexController
{
    use RequestJson;

    /** 获取首页信息 */
    #[GetRoute('/index', false)]
    public function index(): JsonResponse
    {
        $web_setting = setting('web');

        return $this->success($web_setting);
    }
}
