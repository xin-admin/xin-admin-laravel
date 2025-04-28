<?php

namespace App\Generator\Controllers;

use App\Common\Trait\RequestJson;
use App\Generator\Requests\GenRequest;
use App\Generator\Service\ModuleService;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PostMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

#[RequestMapping('/system/gen', 'system.gen')]
class GenController
{
    use RequestJson;

    public array $noPermission = ['generate', 'modules'];

    #[PostMapping]
    public function generate(GenRequest $request): JsonResponse
    {
        $data = $request->validated();
        return $this->success('ok');
    }

    #[GetMapping('/modules')]
    public function modules(): JsonResponse
    {
        $modules = ModuleService::moules();
        return $this->success($modules);
    }

}