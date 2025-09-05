<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Repositories\Sys\SysFileGroupRepository;
use Xin\AnnoRoute\Attribute\Create;
use Xin\AnnoRoute\Attribute\Delete;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\AnnoRoute\Attribute\Update;

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
