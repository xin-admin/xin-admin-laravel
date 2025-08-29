<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FileUpdateInfoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:file,file_id',
            'name' => 'required',
            'file_type' => 'required',
            'file_size' => 'required',
            'file_path' => 'required',
            'file_md5' => 'required',
            'file_sha1' => 'required',
            'file_ext' => 'required',
            'file_width' => 'required',
            'file_height' => 'required',
            'file_duration' => 'required',
            'file_bitrate' => 'required',
        ];
    }
}
