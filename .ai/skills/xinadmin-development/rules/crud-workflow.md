# CRUD Development Workflow

The standard end-to-end workflow for building a new feature in XinAdmin follows four phases. Each phase builds on the last — always work in order.

## Phase 1: Database Migration

Create the database table structure.

```
php artisan make:migration create_xxx_table
```

- Define columns, indexes, and foreign keys in the migration file
- Use `$table->id()` for auto-increment or `$table->string('id', 36)->primary()` for UUIDs
- Use `constrained()` for foreign keys referencing other tables
- Run `php artisan migrate` to apply

## Phase 2: Backend — Controller, Model, FormRequest

### Controller

Create the controller in the appropriate module under `modules/{Module}/Http/Controllers/`. Use AnnoRoute attributes for routing and authorization:

```php
#[RequestAttribute('/system/xxx', 'system.xxx')]
class XxxController extends BaseController
{
    protected array $searchField = ['name' => 'like'];
    protected array $quickSearchField = ['name'];

    #[GetRoute(authorize: 'query')]
    public function query(Request $request): JsonResponse
    {
        $params = $request->all();
        $perPage = (int) ($params['pageSize'] ?? 10);
        $data = $this->buildSearch($params, Model::query())
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        return $this->success($data->toArray());
    }

    #[PostRoute(authorize: 'create')]
    public function create(FormRequest $request): JsonResponse { }

    #[PutRoute(route: '/{id}', authorize: 'update', where: ['id' => '[0-9]+'])]
    public function update(int $id, FormRequest $request): JsonResponse { }

    #[DeleteRoute(route: '/{id}', authorize: 'delete', where: ['id' => '[0-9]+'])]
    public function delete(int $id): JsonResponse { }
}
```

**Key points:**
- `#[RequestAttribute]` sets route prefix and permission prefix — AnnoRoute auto-registers routes (see `rules/annoroute.md`)
- Always use `->paginate($perPage)->toArray()` and pass to `$this->success()` — the PaginationProvider returns `{ data, total, pageSize, current }` which XinTable expects
- Use `$this->buildSearch()` for filter/keyword/sort query building
- Return `$this->success()` or `$this->error()` from BaseController

### Model

Create or reuse the Eloquent model:

```php
class XxxModel extends Model
{
    protected $table = 'xxx';
    protected $fillable = ['name', 'status', /* ... */];
    protected $casts = ['status' => 'integer'];
}
```

### FormRequest

Create for validation on create/update:

```bash
php artisan make:request XxxFormRequest
```

Place in `modules/{Module}/Http/Requests/`. Define `rules()` and `messages()`. Inject via controller method parameter for auto-validation.

## Phase 3: Frontend — Page, Domain, API, i18n

### Domain (`web/domain/`)

TypeScript interfaces matching backend model fields:

```typescript
export interface IXxx {
    id?: number;
    name?: string;
    status?: number;
    created_at?: string;
    updated_at?: string;
}
```

### API (`web/api/{module}/`)

Typed Axios wrappers for each endpoint. Use `createAxios` from `@/utils/request`:

```typescript
import createAxios from '@/utils/request';

export async function getList(params?: Record<string, any>) {
    return createAxios<PaginatorData<IXxx>>({ url: '/system/xxx', method: 'get', params });
}
```

XinTable handles list/create/update/delete automatically — custom API functions are only needed for additional endpoints.

### i18n (`web/locales/{zh_CN,en_US}/`)

Translation keys for the page. Each feature gets its own file:

```typescript
export default {
    'xxx.page.title': 'XXX Management',
    'xxx.id': 'ID',
    'xxx.name': 'Name',
    // ...
};
```

Register in `web/locales/{zh_CN,en_US}/index.ts` by importing and spreading into the default export.

### Page (`web/pages/{module}/xxx/index.tsx`)

Use `<XinTable>` for standard CRUD list pages:

```tsx
import XinTable from '@/components/XinTable';
import type { XinTableColumn } from '@/components/XinTable/typings';
import type { IXxx } from '@/domain/xxx';
import { useTranslation } from 'react-i18next';

export default function XxxPage() {
    const { t } = useTranslation();

    const columns: XinTableColumn<IXxx>[] = [
        { title: t('xxx.id'), dataIndex: 'id', hideInForm: true, width: 80 },
        { title: t('xxx.name'), dataIndex: 'name', valueType: 'text',
          rules: [{ required: true, message: t('xxx.name.required') }] },
    ];

    return (
        <>
            <Title level={3}>{t('xxx.page.title')}</Title>
            <XinTable<IXxx>
                api="/system/xxx"
                columns={columns}
                rowKey="id"
                accessName="system.xxx"
                formProps={{ grid: true, colProps: { span: 12 }, layout: 'vertical' }}
                modalProps={{ width: 800 }}
            />
        </>
    );
}
```

File-system routing auto-maps: `web/pages/system/xxx/index.tsx` → `/system/xxx`

## Phase 4: Menu Routes and Permissions

Add the menu entry in `database/seeders/SysUserSeeder.php` under the appropriate parent menu:

```php
[
    'type' => "route",
    'key' => "system.xxx",
    'name' => "XXX Management",
    "path" => "/system/xxx",
    'local' => "menu.system.xxx",
    'children' => [
        ['type' => 'rule', 'name' => '查询列表', 'key' => 'system.xxx.query'],
        ['type' => 'rule', 'name' => '新增', 'key' => 'system.xxx.create'],
        ['type' => 'rule', 'name' => '更新', 'key' => 'system.xxx.update'],
        ['type' => 'rule', 'name' => '删除', 'key' => 'system.xxx.delete'],
    ]
],
```

Add the menu translation key in `web/locales/{zh_CN,en_US}/menu.ts`:

```text
"menu.system.xxx": "XXX Management",
```

After seeding, the super admin role (role_id=1) automatically gets all permissions via the seeder's auto-assignment logic.

Run `php artisan db:seed --class=SysUserSeeder` to apply.

## Quick Checklist

1. Migration → `database/migrations/`
2. Controller → `modules/{Module}/Http/Controllers/` (with AnnoRoute attributes)
3. Model → `modules/{Module}/Models/` or vendor model
4. FormRequest → `modules/{Module}/Http/Requests/` (for create/update validation)
5. Domain types → `web/domain/xxx.ts`
6. API wrappers → `web/api/{module}/xxx.ts`
7. i18n files → `web/locales/{zh_CN,en_US}/xxx.ts` + register in `index.ts`
8. Page component → `web/pages/{module}/xxx/index.tsx`
9. Menu entry + rules → `database/seeders/SysUserSeeder.php`
10. Menu translation → `web/locales/{zh_CN,en_US}/menu.ts`
