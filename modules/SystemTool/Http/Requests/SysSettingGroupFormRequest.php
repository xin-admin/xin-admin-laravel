<?php

namespace Modules\SystemTool\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Common\Http\Requests\BaseFormRequest;

class SysSettingGroupFormRequest extends BaseFormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        if (!$this->isUpdate()) {
            return [
                'key' => 'required|unique:sys_setting_group,key',
                'title' => 'required',
                'remark' => 'sometimes|required',
            ];
        } else {
            $id = $this->route('id');
            return [
                'key' => ['required', Rule::unique('sys_setting_group', 'key')->ignore($id)],
                'title' => 'required',
                'remark' => 'sometimes|required',
            ];
        }
    }

    public function messages(): array
    {
        return [
            'key.required' => '键名字段是必填的',
            'key.unique' => '键名已存在',
            'title.required' => '标题字段是必填的',
            'remark.required' => '备注字段是必填的',
        ];
    }
}
