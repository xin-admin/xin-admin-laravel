<?php

namespace Xin;

use App\Enum\FileType;
use App\Enum\ShowType as ShopTypeEnum;
use App\Exceptions\HttpResponseException;
use App\Modelss\File\FileModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;

class File
{
    /**
     * 上传文件名
     */
    protected string $fileName = 'file';

    /**
     * 储存空间
     */
    protected string $store = '';

    /**
     * 上传文件对象
     */
    protected ?UploadedFile $files = null;

    public function __construct()
    {
        $this->files = request()->file($this->fileName);
    }

    /**
     * 上传文件
     *
     * @param  int  $fileType  文件类型
     * @param  string  $disk  磁盘信息
     * @param  int  $group_id  分组ID
     * @param  int  $user_id  用户ID
     * @param  int  $channel  来源
     */
    public function upload(int $fileType, string $disk, int $group_id, int $user_id, int $channel = 10): array
    {
        // TODO 未完成
        // 验证文件
        $this->validate($fileType);
        try {
            $filename = $this->files->getClientOriginalName();
            $extension = $this->files->extension();
            $path = $this->files->store('file', $disk);
            $data = [
                'group_id' => $group_id, // 文件分组
                'channel' => $channel,  // 来源
                'storage' => $disk, // 磁盘
                'domain' => '', // 域名
                'file_name' => $filename, // 文件名
                'file_type' => $fileType, // 类型
                'file_path' => $path, // 地址
                'file_size' => $this->files->getSize(), // 大小
                'file_ext' => $extension, // 扩展名
                'uploader_id' => $user_id, // 上传用户ID
            ];
            $fileData = FileModel::query()->create($data);

            return $fileData->toArray();
        } catch (\Exception $e) {
            self::throwError($e->getMessage());
        }
    }

    /**
     * 删除文件
     */
    public function delete(array $fileInfo): bool
    {
        $fileRootPath = Config::get("filesystem.disks.{$fileInfo['domain']}.root");
        // 文件所在目录
        $realPath = realpath($fileRootPath.'/'.$fileInfo['file_path']);

        return $realPath === false || unlink($realPath);
    }

    /**
     * 验证上传的文件
     *
     * @throws HttpResponseException
     */
    protected function validate(int $fileType): void
    {
        $file_ext = $this->files->extension();
        $file_size = $this->files->getSize();

        $fileValidate = FileType::data()[$fileType];
        // 验证文件大小
        if ($file_size > $fileValidate['max_size']) {
            self::throwError('上传文件大小超限制！');
        }
        // 验证扩展名
        if ($fileValidate['file_ext'] === '*') {
            return;
        }
        if (is_array($fileValidate['file_ext'])) {
            if (! in_array($file_ext, $fileValidate['file_ext'])) {
                self::throwError("不支持的{$fileValidate['name']}类型");
            }

            return;
        }
        if ($fileValidate['file_ext'] !== $file_ext) {
            self::throwError("不支持的{$fileValidate['name']}类型");
        }
    }

    /**
     * 抛出错误
     */
    private function throwError($error): void
    {
        throw new HttpResponseException([
            'success' => false,
            'msg' => $error,
            'showType' => ShopTypeEnum::ERROR_MESSAGE->value,
        ]);
    }
}
