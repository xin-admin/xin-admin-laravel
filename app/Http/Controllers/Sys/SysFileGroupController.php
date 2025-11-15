<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysFileGroupRepository;

/**
 * 文件分组控制器
 */
#[RequestMapping('/file/group', 'file.group')]
#[Query, Create, Update, Delete]
class SysFileGroupController extends BaseController
{
    protected function repository(): RepositoryInterface
    {
        return app(SysFileGroupRepository::class);
    }
}
