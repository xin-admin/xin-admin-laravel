# 属性路由（xinadmin/laravel-attr-route）

本项目**不使用 routes 文件注册 HTTP 路由**。所有路由通过控制器上的 PHP 8 属性声明，由 `xinadmin/laravel-attr-route` 包在启动时扫描 `app/Http/Controllers` 自动注册（`config/attr-route.php` 中 `auto_scan => true`）。

## 禁止事项

- 不要创建 `routes/web.php`、`routes/api.php` 或在 `bootstrap/app.php` 的 `withRouting` 中注册web/api路由文件。
- 不要在路由文件里写 `Route::get(...)` 等注册代码；新接口 = 新控制器方法 + 路由属性。

## 类级属性 `#[RequestAttribute]`

标注在控制器类上，四个参数均为命名参数可选：

```php
#[RequestAttribute(
    routePrefix: '/system/user',   // 路由前缀，拼接方法级 route
    abilitiesPrefix: 'system.user',// 权限串前缀，拼接方法级 authorize
    middleware: SomeMiddleware::class, // 类级中间件（字符串或数组）
    authModel: 'default',          // token 所属用户模型别名（config/attr-route.php models）
)]
class SysUserController extends BaseController {}
```

- `#[RequestAttribute]` 不传参时前缀为空（如 `Admin\IndexController` 用 `#[RequestAttribute('/system')]`）。
- AppApi 控制器如 `Api\IndexController` 使用 `#[RequestAttribute('/api')]`。

## 方法级路由属性

可用属性：`GetRoute`、`PostRoute`、`PutRoute`、`PatchRoute`、`DeleteRoute`、`AnyRoute`，命名空间 `Xin\AttrRoute\Attribute`。

```php
#[GetRoute(authorize: 'query')]                       // GET /system/user，权限 system.user.query
#[PostRoute(authorize: 'create')]                     // POST /system/user，权限 system.user.create
#[PutRoute(route: '/{id}', authorize: 'update', where: ['id' => '[0-9]+'])]
#[DeleteRoute(route: '/{id}', authorize: 'delete', where: ['id' => '[0-9]+'])]
#[PostRoute('/login', false, LoginLogMiddleware::class)] // 第二个位置参数 authorize=false 表示公开接口
```

参数说明（均可命名传参）：

- `route`：方法路径，与类前缀拼接；支持 `{id}` 占位符与 `where` 正则约束。
- `authorize`：`true`（默认，仅要求登录）、`false`（公开接口，跳过鉴权）、字符串（与 `abilitiesPrefix` 拼接为完整权限串，校验 Sanctum ability）。
- `middleware`：方法级中间件，类名或数组。
- `where`：路由参数正则约束数组。

## 命名与组织约定

- 一个资源一个控制器，标准方法名：`query`（列表）、`create`、`update`、`delete`，附加动作用驼峰（如 `resetPassword`）。
- 权限串命名：`<区域>.<资源>.<动作>`，如 `system.user.query`、`ai.chat.send`；新模块的权限 key 需同步录入 `sys_rule` 表（见 xinadmin-admin-crud 技能）。
- 方法必须声明 `JsonResponse` 返回类型并通过 `RequestJson`  trait 返回响应。
- 带 `{id}` 参数的方法签名：`public function update(int $id, SysUserFormRequest $request): JsonResponse`（路由参数在前，FormRequest 在后）。
