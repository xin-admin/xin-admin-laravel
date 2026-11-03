<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Xin\AttrRoute\Attribute\GetRoute;
use Xin\AttrRoute\Attribute\RequestAttribute;

#[RequestAttribute]
class SysIndexController extends BaseController
{

    /** 获取首页信息 */
    #[GetRoute('/index', false)]
    public function index(): JsonResponse
    {
        $web_setting = site_config('web', ['success' => true, 'msg' => 'ok']);
        return $this->success($web_setting);
    }

}
