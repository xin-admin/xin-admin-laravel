<?php
namespace App\Models\Sys;

use App\Models\UserModel;
use App\Support\Enum\FileType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * Class File
 */
class SysFileModel extends Model
{
    use SoftDeletes;
    protected $table = 'sys_file';

    protected $primaryKey = 'id';

    protected $casts = [
        'group_id' => 'int',
        'channel' => 'int',
        'file_type' => 'int',
        'file_size' => 'int',
        'uploader_id' => 'int',
    ];

    protected $fillable = [
        'group_id',
        'disk',
        'channel',
        'file_type',
        'file_name',
        'file_path',
        'file_size',
        'file_ext',
        'uploader_id',
    ];

    protected $appends = ['preview_url'];

    /**
     * 获取文件所属分组
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(SysFileGroupModel::class, 'group_id', 'id');
    }

    /**
     * 获取上传者
     * 根据channel字段判断是系统用户还是App用户
     */
    public function uploader(): BelongsTo
    {
        // channel 10:系统用户 20:App用户端
        if ($this->channel == 10) {
            return $this->belongsTo(SysUserModel::class, 'uploader_id', 'id');
        } else {
            return $this->belongsTo(UserModel::class, 'uploader_id', 'id');
        }
    }

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
                return config('app.url').$previewPath;
            }
        );
    }
}
