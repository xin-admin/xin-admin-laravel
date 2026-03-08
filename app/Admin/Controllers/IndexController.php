<?php

namespace App\Admin\Controllers;

use App\Common\Services\AnnoRoute\RequestAttribute;
use App\Common\Services\AnnoRoute\Route\GetRoute;
use App\Common\Trait\RequestJson;
use Illuminate\Http\JsonResponse;

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
