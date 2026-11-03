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
