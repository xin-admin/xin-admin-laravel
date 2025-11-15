<?php

namespace App\Repositories\Sys;

use App\Models\Sys\SysFileModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

class SysFileRepository extends BaseRepository
{
    protected array $searchField = [
        'group_id' => '=',
        'name' => 'like',
        'file_type' => '=',
    ];

    /**
     * @inheritDoc
     */
    protected function model(): Builder
    {
        return SysFileModel::query();
    }

    protected function rules(): array
    {
        return [
            'group_id' => 'required|integer|exists:sys_file_group,id',
            'channel' => 'required|integer|in:10,20',
            'disk' => 'required|string|max:10',
            'file_type' => 'required|integer',
            'file_name' => 'required|string|max:255',
            'file_path' => 'required|string|max:255',
            'file_size' => 'required|integer|min:0',
            'file_ext' => 'required|string|max:20',
            'uploader_id' => 'required|integer',
        ];
    }

    protected function messages(): array
    {
        return [
            // 文件表验证消息
            'group_id.required' => '文件分组ID不能为空',
            'group_id.integer' => '文件分组ID必须是整数',
            'group_id.exists' => '文件分组不存在',

            'channel.required' => '上传来源不能为空',
            'channel.integer' => '上传来源必须是整数',
            'channel.in' => '上传来源必须是10(系统用户)或20(App用户端)',

            'disk.required' => '存储方式不能为空',
            'disk.string' => '存储方式必须是字符串',
            'disk.max' => '存储方式不能超过10个字符',

            'file_type.required' => '文件类型不能为空',
            'file_type.integer' => '文件类型必须是整数',

            'file_name.required' => '文件名称不能为空',
            'file_name.string' => '文件名称必须是字符串',
            'file_name.max' => '文件名称不能超过255个字符',

            'file_path.required' => '文件路径不能为空',
            'file_path.string' => '文件路径必须是字符串',
            'file_path.max' => '文件路径不能超过255个字符',

            'file_size.required' => '文件大小不能为空',
            'file_size.integer' => '文件大小必须是整数',
            'file_size.min' => '文件大小不能为负数',

            'file_ext.required' => '文件扩展名不能为空',
            'file_ext.string' => '文件扩展名必须是字符串',
            'file_ext.max' => '文件扩展名不能超过20个字符',

            'uploader_id.required' => '上传者用户ID不能为空',
            'uploader_id.integer' => '上传者用户ID必须是整数',
        ];
    }
}