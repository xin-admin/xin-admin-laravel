<?php

namespace App\Http\Controllers\Admin;

use App\Attribute\Auth;
use App\Attribute\Monitor;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\RequestMapping;
use App\Models\Admin\AdminGroupModel;
use App\Models\Admin\AdminModel;
use App\Models\Admin\AdminRuleModel;
use App\Trait\RequestJson;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Random\RandomException;
use Xin\Token;

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
