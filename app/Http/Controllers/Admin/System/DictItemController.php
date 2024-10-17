<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\Controller;
use App\Models\Dict\DictItemModel;
use App\Models\Dict\DictModel;
use Illuminate\Http\JsonResponse;

class DictItemController extends Controller
{
    protected string $model = DictItemModel::class;

    protected string $authName = 'system.dict.item';

    protected array $searchField = [
        'name' => 'like',
        'dict_id' => '='
    ];

    protected array $rule = [
        'label' => 'required',
        'value' => 'required',
        'status' => 'required',
        'switch' => 'required'
    ];

    /**
     * 获取字典列表
     */
    public function dictList(): JsonResponse
    {
        $dict = DictModel::with('dictItems:dict_id,label,value,status')->get();
        foreach ($dict as $post) {
            foreach ($post->dictItems as $comment) {
                $comment->makeHidden(['dict_id']);
            }
        }
        $dict = $dict->toArray();
        return $this->success($dict);
    }
}
