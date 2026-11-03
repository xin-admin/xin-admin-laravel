<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\AgentModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AttrRoute\Attribute\GetRoute;
use Xin\AttrRoute\Attribute\PutRoute;
use Xin\AttrRoute\Attribute\RequestAttribute;

#[RequestAttribute('/ai/agent', 'ai.agent')]
class AgentController extends BaseController
{
    #[GetRoute(authorize: 'query')]
    public function query(): JsonResponse
    {
        $agents = AgentModel::orderBy('id')->get();
        return $this->success($agents->toArray());
    }

    #[GetRoute('/{id}', authorize: 'query', where: ['id' => '[0-9]+'])]
    public function show(int $id): JsonResponse
    {
        $agent = AgentModel::find($id);
        if (! $agent) {
            return $this->error('Agent not found');
        }
        return $this->success($agent->toArray());
    }

    #[PutRoute('/{id}', authorize: 'update', where: ['id' => '[0-9]+'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $enabled = $request->boolean('enabled', true);
        $model = AgentModel::find($id);
        if (! $model) {
            return $this->error('Agent not found');
        }
        $model->enabled = $enabled;
        $model->save();
        return $this->success();
    }
}
