<?php

namespace App\Controllers\System;

use App\Controllers\BaseController;
use App\Services\AnnoRoute\Crud\Create;
use App\Services\AnnoRoute\Crud\Delete;
use App\Services\AnnoRoute\Crud\Query;
use App\Services\AnnoRoute\Crud\Update;
use App\Services\AnnoRoute\RequestAttribute;
use App\Services\System\SysDictService;

/**
 * 字典管理
 */
#[RequestAttribute('/system/dict/list', 'system.dict.list')]
#[Query, Create, Update, Delete]
class SysDictController extends BaseController
{

    public function __construct(
        protected SysDictService $service
    ) {}

}
