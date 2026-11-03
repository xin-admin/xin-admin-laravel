<laravel-boost-guidelines>
=== .ai/xinadmin/api-conventions rules ===

# API 开发约定

## 列表查询（BaseController::buildSearch）

列表接口使用 `BaseController` 的 `buildSearch(array $params, Builder $query)` 构建查询，配合两个类属性声明可搜索字段：

```php
class SysDictController extends BaseController
{
    /** 搜索字段：字段名 => 操作符 */
    protected array $searchField = ['name' => 'like', 'status' => '='];

    /** 快速搜索字段（keywordSearch 参数跨字段模糊） */
    protected array $quickSearchField = ['name', 'code'];
}
```

支持的操作符：`=`、`>`、`<>`、`<`、`>=`、`<=`、`like`（%v%）、`afterLike`（v%）、`beforeLike`（%v）、`date`（whereDate）、`betweenDate`（日期区间数组）。

请求参数约定（前端 Ant Design Pro 表格传入）：

- `pageSize`：每页条数，列表方法内 `$params['pageSize'] ?? 10` 后 `->paginate($pageSize)`。
- `filter`：JSON 字符串或数组，`{字段: [值1, 值2]}`，构建 `whereIn`。
- `sorter`：JSON 字符串或数组 `{字段: 'ascend'|'descend'}`，构建排序。
- `keywordSearch`：快速搜索关键词，在 `quickSearchField` 字段间 `whereAny like`。

列表方法标准写法：

```php
#[GetRoute(authorize: 'query')]
public function query(Request $request): JsonResponse
{
    $params = $request->all();
    $pageSize = $params['pageSize'] ?? 10;
    $query = SysXxxModel::query();
    $data = $this->buildSearch($params, $query)
        ->paginate($pageSize)
        ->toArray();
    return $this->success($data);
}
```

## 表单验证

- 写操作使用 FormRequest，继承 `App\Http\Requests\BaseFormRequest`（不是直接继承 `FormRequest`），放在 `app/Http/Requests/Admin/`（或对应区域目录）。
- 创建/更新共用同一个 FormRequest，用 `$this->isUpdate()`（PUT/PATCH 为 true）分支返回不同规则；更新时唯一性规则用 `Rule::unique(...)->ignore($this->route('id'))`。
- 类属性 `protected $stopOnFirstFailure = true;`。
- 必须实现 `messages()` 返回**中文**错误信息。
- 轻量验证（如重置密码的 id+password）可直接在控制器 `$request->validate([...], [中文消息])`。

## 响应与中断

- 成功：`return $this->success($data);`；失败：`return $this->error('中文提示');`。
- 深层逻辑中需要中断时，用 `$this->throwError('...')` / `$this->throwSuccess($data)` 抛出响应，不要层层 return。
- 响应结构固定为 `{data, success, msg, showType}`，不要返回其他结构；showType 由 `App\Enum\ShowType` 控制。

## 数据库与迁移

- 迁移内每张表用 `if (! Schema::hasTable('xxx'))` 包裹；字段加 `->comment('中文说明')`，表加 `->comment()`；主键 `$table->increments('id')`；按需 `$table->timestamps()` / `$table->softDeletes()`。
- 一个迁移文件可创建同模块的多张关联表。
- 状态字段语义注意区分：`sys_user/sys_role/sys_rule.status` 为 1 正常 0 停用，`sys_dept.status` 为 0 正常 1 停用 —— 使用前查迁移注释，不要想当然。

## 其他

- 时间字段在模型 `$casts` 中声明 `'datetime'`。
- 国际化文案使用 `__('user.xxx')`、`__('system.error.xxx')` 键；控制器内硬编码提示直接写中文。
- 树形数据构建用全局助手 `getTreeData($list)`（id/parent_id/children 字段名可通过第二参数覆盖）。

=== .ai/xinadmin/architecture rules ===

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

=== .ai/xinadmin/attr-route rules ===

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

=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application running on PHP 8.3. You are an expert with the Laravel ecosystem. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Use `search-docs` before changes that depend on Laravel ecosystem APIs, behavior, configuration, or version-specific syntax. Skip it for copy-only edits and other changes where package documentation is irrelevant. Reuse sufficient results already in context instead of searching again.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists (settled decisions, non-obvious traps, standing constraints). Framework and package guidelines that only apply to specific paths (testing, frontend, components) also live there, under `.ai/rules/boost` — this is not just recorded decisions, it is load-bearing guidance you have not seen inline. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.
- Record a rule with `record-rule` only when the user explicitly asks for one. Instructions for the work at hand are not rules, no matter how emphatic: "remove this typo", "use X here" are work to do, not rules to record. Never record a rule on your own initiative, as a byproduct of a change, or to summarize what you just did. When the user does ask, pass a `glob` (e.g. `app/Http/Controllers/**`), a short `title`, and a few-line `note`. Use `record-rule` rather than your native memory or notes tool, because native memory is personal and session-scoped, while only `.ai/rules` is shared with the team and persists in the repo.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Follow existing application Enum naming conventions.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.
- Activate the `deploying-to-cloud` skill whenever deploying to Laravel Cloud, configuring Cloud environments or resources, using the Cloud CLI, or troubleshooting Cloud deployments.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== phpunit/core rules ===

# PHPUnit

- This project uses PHPUnit. Create tests with `php artisan make:test --phpunit {name}`.
- Do not include the test suite directory in `{name}`. Use `SomeFeatureTest`, not `Feature/SomeFeatureTest`.
- Read the `testing-best-practices` skill for guidance on coverage, naming, structure, dependency isolation, and review.

## Running Tests

- Run the narrowest set of tests that covers the change. Pass a file path or `--filter=testName` to `php artisan test --compact`.
- Rerun a test after each change to it.
- Run `vendor/bin/phpunit` to call the test runner directly. It accepts the same file path and `--filter=testName` arguments.

</laravel-boost-guidelines>
