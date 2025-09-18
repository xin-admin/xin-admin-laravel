<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\Sys\SysDictItemRepository;

/**
 * 字典项控制器
 */
#[RequestMapping('/system/dict/item')]
#[Query, Create, Update, Delete]
class SysDictItemController extends BaseController
{
    public function __construct(SysDictItemRepository $repository)
    {
        $this->repository = $repository;
    }
}
