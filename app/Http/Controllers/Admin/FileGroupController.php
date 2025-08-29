<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\FileGroupRequest;
use App\Models\FileGroupModel;
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
class FileGroupController extends BaseController
{
    protected string $model = FileGroupModel::class;
    protected string $formRequest = FileGroupRequest::class;
    protected array $searchField = ['name' => 'like'];
}
