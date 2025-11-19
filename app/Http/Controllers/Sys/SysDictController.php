<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysDictRepository;

/**
 * 字典管理
 */
#[RequestMapping('/sys/dict/list', 'system.dict.list')]
#[Query, Create, Update, Delete]
class SysDictController extends BaseController
{
    protected array $noPermission = ['itemList'];

    protected function repository(): RepositoryInterface
    {
        return app(SysDictRepository::class);
    }
}
