<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Crud\Create;
use App\Providers\AnnoRoute\Crud\Delete;
use App\Providers\AnnoRoute\Crud\Query;
use App\Providers\AnnoRoute\Crud\Update;
use App\Providers\AnnoRoute\RequestAttribute;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysDictRepository;

/**
 * 字典管理
 */
#[RequestAttribute('/sys/dict/list', 'system.dict.list')]
#[Query, Create, Update, Delete]
class SysDictController extends BaseController
{
    protected string $repository = SysDictRepository::class;
}
