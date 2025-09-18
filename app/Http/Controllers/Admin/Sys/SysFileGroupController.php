<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\Sys\SysFileGroupRepository;

/**
 * 文件分组控制器
 */
#[RequestMapping('/file/group', 'file.group')]
#[Query, Create, Update, Delete]
class SysFileGroupController extends BaseController
{
    public function __construct(SysFileGroupRepository $repository)
    {
        $this->repository = $repository;
    }
}
