<?php

namespace App\Http\Controllers\Admin\File;

use App\Attribute\Auth;
use App\Http\Controllers\Admin\Controller;
use App\Models\File\FileModel;
use Illuminate\Http\JsonResponse;
use Xin\File;

class FileController extends Controller
{
    protected string $model = FileModel::class;

    protected string $authName = 'file.file';

    protected array $searchField = [
        'group_id' => '=',
        'name' => 'like',
        'file_type' => '=',
    ];

    /**
     * 基础控制器删除方法
     */
    #[Auth('delete')]
    public function delete(): JsonResponse
    {
        $data = request()->all();
        if (! isset($data['ids'])) {
            return $this->error('请选择ID');
        }
        $delArr = explode(',', $data['ids']);
        // 验证文件数量
        if (count($delArr) > 15) {
            return $this->error('一次性最多删除15个文件');
        }
        foreach ($delArr as $fileId) {
            // 获取文件详情
            $fileInfo = $this->model::query()->find($fileId);
            // 实例化存储驱动
            $storage = new File;
            // 删除文件
            if (! $storage->delete($fileInfo->toArray())) {
                return $this->error('文件删除失败：' . $storage->getError());
            }
            // 标记为已删除
            $fileInfo->delete();
        }

        return $this->success('删除成功，删除了' . count($delArr) . '个文件数据');
    }
}
