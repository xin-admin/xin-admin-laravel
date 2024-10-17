<?php
namespace App\Http\Controllers\Admin\File;

use App\Attribute\Auth;
use App\Enum\FileType;
use App\Http\Controllers\Admin\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\File;

class UploadController extends Controller
{

    protected string $authName = 'file.upload';

    /**
     * 上传图片
     */
    #[Auth('image')]
    public function image(Request $request, int $groupId = 0): JsonResponse
    {
        // 实例化存储驱动
        $storage = new File();
        $user_id = Auth::getAdminId();
        // 执行文件上传
        $fileInfo = $storage->upload(
            FileType::IMAGE->value,
            'public',
            $groupId,
            $user_id,
            20
        );
        return $this->success(['fileInfo' => $fileInfo], '图片上传成功');
    }

    /**
     * 上传视频
     */
    #[Auth('video')]
    public function video(Request $request, int $groupId = 0): JsonResponse
    {
        // 实例化存储驱动
        $storage = new File();
        $user_id = Auth::getAdminId();
        // 执行文件上传
        $fileInfo = $storage->upload(
            FileType::VIDEO->value,
            'public',
            $groupId,
            $user_id,
            20
        );
        return $this->success(['fileInfo' => $fileInfo], '视频上传成功');
    }

    /**
     * 上传压缩文件
     */
    #[Auth('zip')]
    public function zip(Request $request, int $groupId = 0): JsonResponse
    {
        // 实例化存储驱动
        $storage = new File();
        $user_id = Auth::getAdminId();
        // 执行文件上传
        $fileInfo = $storage->upload(
            FileType::ZIP->value,
            'public',
            $groupId,
            $user_id,
            20
        );
        return $this->success(['fileInfo' => $fileInfo], '上传成功');
    }

    /**
     * 上传音频文件
     */
    #[Auth('mp3')]
    public function mp3(Request $request, int $groupId = 0): JsonResponse
    {
        // 实例化存储驱动
        $storage = new File();
        $user_id = Auth::getAdminId();
        // 执行文件上传
        $fileInfo = $storage->upload(
            FileType::MP3->value,
            'public',
            $groupId,
            $user_id,
            20
        );
        return $this->success(['fileInfo' => $fileInfo], '上传成功');
    }

    /**
     * 上传其他类型文件
     */
    #[Auth('annex')]
    public function annex(Request $request, int $groupId = 0): JsonResponse
    {
        // 实例化存储驱动
        $storage = new File();
        $user_id = Auth::getAdminId();
        // 执行文件上传
        $fileInfo = $storage->upload(
            FileType::ANNEX->value,
            'public',
            $groupId,
            $user_id,
            20
        );
        return $this->success(['fileInfo' => $fileInfo], '上传成功');
    }

}
