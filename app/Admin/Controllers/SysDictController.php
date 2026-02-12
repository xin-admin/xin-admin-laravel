<?php

namespace App\Admin\Controllers;

use App\Admin\Services\SysDictService;
use App\Common\Controllers\BaseController;
use App\Common\Services\AnnoRoute\Crud\Create;
use App\Common\Services\AnnoRoute\Crud\Delete;
use App\Common\Services\AnnoRoute\Crud\Query;
use App\Common\Services\AnnoRoute\Crud\Update;
use App\Common\Services\AnnoRoute\RequestAttribute;

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
