<?php

namespace Modules\SystemAgent\Http\Requests;

use Modules\Common\Http\Requests\BaseFormRequest;

class AgentFormRequest extends BaseFormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'enabled' => 'required|boolean',
            'name' => 'nullable|max:100',
            'description' => 'nullable|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'enabled.required' => '启用状态不能为空',
            'enabled.boolean' => '启用状态格式错误',
            'name.max' => '名称不能超过100个字符',
            'description.max' => '描述不能超过1000个字符',
        ];
    }
}
