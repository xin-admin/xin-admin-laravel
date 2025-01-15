<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Enum\FileType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class File
 *
 * @property int $file_id
 * @property int $group_id
 * @property int $channel
 * @property string $storage
 * @property string $domain
 * @property int $file_type
 * @property string $file_name
 * @property string $file_path
 * @property int $file_size
 * @property string $file_ext
 * @property string $cover
 * @property int $uploader_id
 * @property int $is_recycle
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @mixin IdeHelperModel
 */
class FileModel extends Model
{
    use SoftDeletes;
    protected $table = 'file';

    protected $primaryKey = 'file_id';

    protected $casts = [
        'group_id' => 'int',
        'channel' => 'int',
        'file_type' => 'int',
        'file_size' => 'int',
        'uploader_id' => 'int',
        'is_recycle' => 'int',
    ];

    protected $fillable = [
        'group_id',
        'channel',
        'storage',
        'domain',
        'file_type',
        'file_name',
        'file_path',
        'file_size',
        'file_ext',
        'cover',
        'uploader_id',
        'is_recycle',
    ];

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
