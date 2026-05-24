<?php
namespace Modules\SystemTool\Models;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Modules\SystemTool\Enum\FileType;
use Modules\SystemUser\Models\SysUserModel;

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

    protected $appends = ['preview_url', 'file_url'];

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
                try {
                    // 图片类型：直接返回图片URL作为预览
                    if ($data['file_type'] === FileType::IMAGE->value) {
                        if($data['disk'] === 'local') {
                            return Storage::disk($data['disk'])->url($data['file_path']);
                        }
                        return Storage::disk($data['disk'])->temporaryUrl(
                            $data['file_path'], now()->plus(minutes: 5)
                        );
                    }
                    $fileType = FileType::tryFrom($data['file_type']);
                    // 其他类型：返回默认类型图标
                    $previewPath = $fileType?->previewPath() ?? FileType::ANNEX->previewPath();
                    return config('app.url') . '/' . $previewPath;

                } catch (\Throwable $e) {
                    // 发生异常时返回默认图标
                    return config('app.url') . '/' . FileType::ANNEX->previewPath();
                }
            }
        );
    }

    /**
     * 获取文件访问URL
     */
    protected function fileUrl(): Attribute
    {
        return new Attribute(
            get: function ($value, array $data) {
                try {
                    return Storage::disk($data['disk'])->url($data['file_path']);
                } catch (\Throwable $e) {
                    return null;
                }
            }
        );
    }
}
