# XinAdmin 项目架构

## 项目定位

这是一个**纯 API 后端**项目（无 Blade 视图、无 Vite 前端、无 routes/web.php 与 routes/api.php），对外提供两组 API：

- **AdminApi**：`app/Http/Controllers/Admin/` —— 中后台管理端接口（用户、角色、权限、字典、文件、站点配置、AI 聊天等）。
- **AppApi**：`app/Http/Controllers/Api/` —— 面向 App/前台的公开接口。

前端为独立项目（Ant Design Pro 风格），后端所有响应遵循 `{data, success, msg, showType}` 结构。

## 目录约定（与 Laravel 默认结构不同，必须遵守）

- 中间件目录是 `app/Http/Middlewares`（复数），**不是** `app/Http/Middleware`；命名空间 `App\Http\Middlewares`。
- Trait 目录是 `app/Trait`（单数），命名空间 `App\Trait`。
- 模型统一带 `Model` 后缀（如 `SysUserModel`），通过 `protected $table` 显式指定蛇形表名。
- 枚举放在 `app/Enum`（如 `ShowType`、`FileType`），均为 PHP 8.3 原生 enum，带中文注释。
- 业务服务放在 `app/Services`（如 `SysFileService`、`SysSiteConfigService`），普通类，直接 `new` 或静态调用，不注册门面。
- AI 智能体放在 `app/Ai/Agents`（基于 laravel/ai SDK）。
- 全局辅助函数在 `app/helpers.php`：`site_config('group.key')` 读取站点配置、`getTreeData()` 构建树、`web_path()`。
- 注释与校验错误信息使用**中文**；PHPDoc 块优先于行内注释。

## 认证与权限

- 认证使用 **Laravel Sanctum**，token 存于 `sys_access_token` 表（`Sanctum::usePersonalAccessTokenModel(SysAccessToken::class)`）。
- 权限粒度为 **Sanctum abilities**，权限串形如 `system.user.query`，与 `sys_rule` 表的 `key` 字段一一对应。
- `SysUserModel::access()` 返回当前用户全部权限串；`id === 1` 为超级管理员，自动拥有全部权限。
- 权限不足由 Sanctum `MissingAbilityException` 捕获，`app/Exceptions/ExceptionsHandler.php` 统一渲染为通知式 JSON。

## 统一响应

所有控制器继承 `App\Http\Controllers\BaseController` 并使用 `App\Trait\RequestJson`：

- `$this->success($data)` / `$this->error($msg)` / `$this->warn($msg)` / `$this->notification(...)` 返回 JsonResponse。
- `$this->throwSuccess(...)` / `$this->throwError(...)` 抛出 `HttpResponseException` 中断执行，由异常处理器渲染。
- 响应类型枚举为 `App\Enum\ShowType`（0 成功消息 / 1 警告 / 2 错误 / 3-5 通知 / 99 静默）。

## 关键基础设施

- **属性路由**：所有路由通过控制器 PHP 8 属性声明（见 xinadmin/attr-route 指南），不要创建 routes/web.php 或 routes/api.php。
- **异常处理**：`App\Exceptions\ExceptionsHandler` 将所有异常渲染为 JSON 并附加 CORS 头；业务中断优先用 throw 响应而非手动 return。
- **站点配置**：站点展示类配置（web 组，如网站标题/LOGO）走 `site_config()`（`SysSiteConfigService`）；邮件、存储、AI 等框架服务配置遵循 Laravel 约定，走 `config()` + env，不做数据库在线配置。
