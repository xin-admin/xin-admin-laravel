<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Xin\AttrRoute\Attribute\GetRoute;
use Xin\AttrRoute\Attribute\RequestAttribute;

#[RequestAttribute('/api')]
class IndexController extends BaseController
{

    /** 获取首页信息 */
    #[GetRoute('/index')]
    public function index(): JsonResponse
    {
        $web_setting = site_config('web');

        return $this->success(compact('web_setting'));
    }
}
