<?php

namespace App\Admin\Controllers;

use App\Admin\Services\SysDictItemService;
use App\Common\Controllers\BaseController;
use App\Common\Services\AnnoRoute\Crud\Create;
use App\Common\Services\AnnoRoute\Crud\Delete;
use App\Common\Services\AnnoRoute\Crud\Query;
use App\Common\Services\AnnoRoute\Crud\Update;
use App\Common\Services\AnnoRoute\RequestAttribute;

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
