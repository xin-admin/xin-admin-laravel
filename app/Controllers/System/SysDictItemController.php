<?php

namespace App\Controllers\System;

use App\Controllers\BaseController;
use App\Repositories\System\SysDictItemRepository;
use App\Services\AnnoRoute\Crud\Create;
use App\Services\AnnoRoute\Crud\Delete;
use App\Services\AnnoRoute\Crud\Query;
use App\Services\AnnoRoute\Crud\Update;
use App\Services\AnnoRoute\RequestAttribute;

/**
 * 字典项控制器
 */
#[RequestAttribute('/sys/dict/item', 'system.dict.item')]
#[Query, Create, Update, Delete]
class SysDictItemController extends BaseController
{
    protected string $repository = SysDictItemRepository::class;
}
