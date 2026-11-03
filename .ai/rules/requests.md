---
paths:
  - 'app/Http/Requests/**'
---

# Requests

## FormRequest 继承 BaseFormRequest
FormRequest 继承 App\Http\Requests\BaseFormRequest，设置 $stopOnFirstFailure = true。创建与更新共用一个类：用 $this->isUpdate()（PUT/PATCH）分支规则，更新时唯一性用 Rule::unique(...)->ignore($this->route('id'))。messages() 必须返回中文错误消息。参考 SysUserFormRequest。
