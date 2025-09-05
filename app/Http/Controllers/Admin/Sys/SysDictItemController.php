<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Repositories\Sys\SysDictItemRepository;
use Xin\AnnoRoute\Attribute\Create;
use Xin\AnnoRoute\Attribute\Delete;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\AnnoRoute\Attribute\Update;

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
