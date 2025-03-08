<?php
namespace App\Models;

use App\Enum\FileType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * Class File
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
        'disk',
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
            get: function ($value, array $data) {
                // 图片的预览图直接使用外链
                if ($data['file_type'] == FileType::IMAGE->value) {
                    return Storage::disk($data['disk'])->url($data['file_path']);
                }
                // 生成默认的预览图
                $previewPath = FileType::from($data['file_type'])->previewPath();
                return env('APP_URL').$previewPath;
            }, set: fn () => null
        );
    }
}
