<?php


namespace App\Models\File;

use App\Enum\FileType;
use App\Enum\ShowType;
use App\Exception\HttpResponseException;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * 文件模型
 */
class FileModel extends BaseModel
{
    protected $table = 'file';

    // 定义主键
    protected $primaryKey = 'file_id';

    protected $fillable = ['group_id', 'channel', 'storage', 'domain', 'file_name', 'file_type', 'uploader_id', 'file_ext', 'file_path', 'file_size'];

    protected $appends = ['preview_url'];

    protected function previewUrl(): Attribute
    {
        return new Attribute(
            get: function ($value, $data) {
                // 图片的预览图直接使用外链
                if ($data['file_type'] == FileType::IMAGE->value) {
                    if ($data['storage'] == 'public') {
                        $data['domain'] = rtrim(request()->schemeAndHttpHost().'/storage/', '/');
                    }

                    return "{$data['domain']}/{$data['file_path']}";
                }
                // 生成默认的预览图
                $previewPath = FileType::data()[$data['file_type']]['preview_path'];

                return request()->schemeAndHttpHost().$previewPath;
            }, set: fn () => null
        );
    }
}
