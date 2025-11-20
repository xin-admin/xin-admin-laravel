<?php

namespace App\Http\Controllers;

use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Support\Trait\RequestJson;
use Illuminate\Http\JsonResponse;

#[RequestMapping]
class IndexController
{
    use RequestJson;

    protected array $noPermission = ['index'];

    /** 获取首页信息 */
    #[GetMapping('/index')]
    public function index(): JsonResponse
    {
        $web_setting = setting('web');

        return $this->success($web_setting);
    }
}
