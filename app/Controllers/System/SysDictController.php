<?php

namespace App\Controllers\System;

use App\Controllers\BaseController;
use App\Repositories\System\SysDictRepository;
use App\Services\AnnoRoute\Crud\Create;
use App\Services\AnnoRoute\Crud\Delete;
use App\Services\AnnoRoute\Crud\Query;
use App\Services\AnnoRoute\Crud\Update;
use App\Services\AnnoRoute\RequestAttribute;

/**
 * 字典管理
 */
#[RequestAttribute('/sys/dict/list', 'system.dict.list')]
#[Query, Create, Update, Delete]
class SysDictController extends BaseController
{
    protected string $repository = SysDictRepository::class;

}
