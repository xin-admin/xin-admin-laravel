<?php

namespace Modules\SystemTool\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\AnnoRoute\Attribute\GetRoute;
use Modules\AnnoRoute\Attribute\RequestAttribute;
use Modules\Common\Http\Controllers\BaseController;

#[RequestAttribute]
class SysIndexController extends BaseController
{

    /** 获取首页信息 */
    #[GetRoute('/index', false)]
    public function index(): JsonResponse
    {
        $web_setting = site_config('web');
        return $this->success($web_setting);
    }

}
