<?php

namespace App\Http;

use App\Models\BaseModel;
use App\Trait\RequestJson;

// 基础控制器
abstract class BaseController
{
    use RequestJson;

    /**
     * 权限验证白名单
     */
    protected array $noPermission = [];
}
