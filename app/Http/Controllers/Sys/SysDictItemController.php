<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Crud\Create;
use App\Providers\AnnoRoute\Crud\Delete;
use App\Providers\AnnoRoute\Crud\Query;
use App\Providers\AnnoRoute\Crud\Update;
use App\Providers\AnnoRoute\RequestAttribute;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysDictItemRepository;
use App\Repositories\Sys\SysDictRepository;

/**
 * 字典项控制器
 */
#[RequestAttribute('/sys/dict/item', 'system.dict.item')]
#[Query, Create, Update, Delete]
class SysDictItemController extends BaseController
{
    protected string $repository = SysDictItemRepository::class;
}
