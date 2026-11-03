---
name: xinadmin-admin-crud
description: "Scaffold a new Admin API module (CRUD) in the XinAdmin Laravel backend. Use when creating a new admin resource end-to-end: migration with hasTable guards and Chinese comments, Model-suffixed Eloquent model, BaseFormRequest with isUpdate branches and Chinese messages, attribute-routed controller extending BaseController with Sanctum abilities, and sys_rule permission entries. Also use when adding list/search pages using buildSearch, or when modifying existing Admin controllers like SysUserController."
---

# XinAdmin 后台模块脚手架

新增一个后台管理模块（CRUD）的完整流程。所有代码遵循项目现有约定，参考样板：`SysUserController` / `SysUserModel` / `SysUserFormRequest` / `2025_01_01_000001_create_sys_user_table.php`。

## 前置检查

1. 表结构先用 Boost `database-schema` 工具确认是否已存在；新表才写迁移。
2. 路由**不要**写进 routes 文件 —— 本项目用属性路由（见 `.ai/guidelines/xinadmin/attr-route.md`）。
3. 命名：以 `sys_dict`（字典）为例 —— 表 `sys_xxx`、模型 `SysXxxModel`、控制器 `SysXxxController`、请求类 `SysXxxFormRequest`、权限前缀 `module.xxx`。

## 第一步：迁移

`php artisan make:migration create_xxx_table --no-interaction`，然后按项目约定编写：

- 每张表用 `if (! Schema::hasTable('xxx'))` 包裹，`down()` 中对应 `dropIfExists`。
- 主键 `$table->increments('id')`（项目统一用 increments，不是 id()）。
- 每个字段 `->comment('中文说明')`，表末尾 `$table->comment('中文表名')`。
- 常规字段：`status`（int，默认 1，注释写明 1 正常 0 停用）、`$table->timestamps()`、需要回收站的表加 `$table->softDeletes()`。
- 一个迁移文件可包含同模块多张表（主表 + 关联表）。

## 第二步：模型

`app/Models/SysXxxModel.php`（不要放别的目录，必须 `Model` 后缀）：

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 系统Xxx模型
 */
class SysXxxModel extends Model
{
    use SoftDeletes; // 按需

    protected $table = 'sys_xxx';
    protected $primaryKey = 'id';

    protected $fillable = [/* 可写字段 */];

    protected $casts = [
        'status' => 'integer',
        // 时间字段 => 'datetime',
    ];
}
```

- 关联关系方法带返回类型声明（`HasMany` / `BelongsTo` / `BelongsToMany`）和中文 PHPDoc。
- 附加字段用 `$appends` + `getXxxAttribute()` 访问器；需隐藏关联原始数据时加进 `$hidden`。

## 第三步：FormRequest

`app/Http/Requests/Admin/SysXxxFormRequest.php`，继承 `App\Http\Requests\BaseFormRequest`：

```php
class SysXxxFormRequest extends BaseFormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        if (! $this->isUpdate()) {
            return [
                'name' => 'required|unique:sys_xxx,name',
                // 创建规则...
            ];
        }
        $id = $this->route('id');
        return [
            'name' => ['required', Rule::unique('sys_xxx', 'name')->ignore($id)],
            // 更新规则（通常去掉密码类字段）...
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '名称不能为空',
            'name.unique' => '名称已存在',
            // 全中文消息...
        ];
    }
}
```

## 第四步：控制器

`app/Http/Controllers/Admin/SysXxxController.php`，继承 `BaseController`，用属性路由：

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SysXxxFormRequest;
use App\Models\SysXxxModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AttrRoute\Attribute\DeleteRoute;
use Xin\AttrRoute\Attribute\GetRoute;
use Xin\AttrRoute\Attribute\PostRoute;
use Xin\AttrRoute\Attribute\PutRoute;
use Xin\AttrRoute\Attribute\RequestAttribute;

/**
 * Xxx管理控制器
 */
#[RequestAttribute('/system/xxx', 'system.xxx')]
class SysXxxController extends BaseController
{
    protected array $searchField = ['name' => 'like', 'status' => '='];
    protected array $quickSearchField = ['name'];

    /** 查询列表 */
    #[GetRoute(authorize: 'query')]
    public function query(Request $request): JsonResponse
    {
        $params = $request->all();
        $pageSize = $params['pageSize'] ?? 10;
        $data = $this->buildSearch($params, SysXxxModel::query())
            ->paginate($pageSize)
            ->toArray();
        return $this->success($data);
    }

    /** 创建 */
    #[PostRoute(authorize: 'create')]
    public function create(SysXxxFormRequest $request): JsonResponse
    {
        $model = SysXxxModel::create($request->validated());
        if (empty($model)) {
            return $this->error();
        }
        return $this->success();
    }

    /** 更新 */
    #[PutRoute(route: '/{id}', authorize: 'update', where: ['id' => '[0-9]+'])]
    public function update(int $id, SysXxxFormRequest $request): JsonResponse
    {
        $model = SysXxxModel::find($id);
        if (empty($model)) {
            return $this->error();
        }
        $model->update($request->validated());
        return $this->success();
    }

    /** 删除 */
    #[DeleteRoute(route: '/{id}', authorize: 'delete', where: ['id' => '[0-9]+'])]
    public function delete(int $id): JsonResponse
    {
        $model = SysXxxModel::find($id);
        if (empty($model)) {
            $this->throwError('数据不存在');
        }
        $model->delete();
        return $this->success();
    }
}
```

要点：

- 方法名固定 `query/create/update/delete`；附加动作驼峰命名并显式传 route（如 `#[PutRoute('/resetPassword', 'resetPassword')]`）。
- 公开接口才用 `authorize: false`；需要登录但不要求权限用 `authorize: true`（默认值，可省略）。
- 选项类接口（下拉数据源）参考 `SysUserController::role()` / `dept()`。

## 第五步：权限与菜单（sys_rule）

新模块的权限串必须录入 `sys_rule` 表，否则非超管用户无法访问：

- `type` 取值：`'menu'`（菜单）、`'route'`（页面路由）、`'rule'`（按钮/接口权限）。
- `key` 与控制器权限串完全一致：`system.xxx.query`、`system.xxx.create`、`system.xxx.update`、`system.xxx.delete` 等。
- 参考 `database/seeders/SysDataSeeder.php` 的现有写法，在 seeder 中追加菜单 + 权限节点，然后 `php artisan db:seed --class=SysDataSeeder`。
- 超管（id=1）自动拥有全部 `status=1` 的权限；其他用户通过角色关联 `sys_role_rule`。

## 验证

1. `php artisan route:list --path=system/xxx` 确认路由已注册。
2. 用超管 token 调一遍 query/create/update/delete（`php artisan test` 或 curl）。
3. 确认权限不足的 token 收到通知式 JSON（`MissingAbilityException` 渲染结果）。
