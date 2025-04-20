<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Requests\SystemRequest\DictItemRequest;
use App\Http\BaseController;
use App\Models\DictItemModel;
use Xin\AnnoRoute\Attribute\Create;
use Xin\AnnoRoute\Attribute\Delete;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\AnnoRoute\Attribute\Update;

/**
 * 字典项控制器
 */
#[RequestMapping('/system/dict/item')]
#[Query, Create, Update, Delete]
class DictItemController extends BaseController
{
    protected string $model = DictItemModel::class;
    protected string $formRequest = DictItemRequest::class;
    protected array $searchField = [
        'name' => 'like',
        'dict_id' => '=',
    ];
}
