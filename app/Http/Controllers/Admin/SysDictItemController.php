<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\SysDictItemService;
use Xin\AnnoRoute\Crud\Create;
use Xin\AnnoRoute\Crud\Delete;
use Xin\AnnoRoute\Crud\Query;
use Xin\AnnoRoute\Crud\Update;
use Xin\AnnoRoute\RequestAttribute;

/**
 * 字典项控制器
 */
#[RequestAttribute('/system/dict/item', 'system.dict.item')]
#[Query, Create, Update, Delete]
class SysDictItemController extends BaseController
{

    public function __construct(
        protected SysDictItemService $service
    ) {}

}
