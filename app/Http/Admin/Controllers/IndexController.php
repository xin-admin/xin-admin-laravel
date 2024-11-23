<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\route\GetMapping;
use App\Attribute\route\RequestMapping;
use App\Trait\RequestJson;
use Illuminate\Http\JsonResponse;

#[RequestMapping('/admin')]
class IndexController
{
    use RequestJson;

    #[GetMapping]
    public function index(): JsonResponse
    {
        $webSetting = get_setting('web');
        return $this->success(compact('webSetting'), '恭喜你已经成功安装 Xin Admin');
    }
}
