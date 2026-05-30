# i18n Conventions

Locale files must follow a consistent directory structure and naming convention. All keys are dot-separated, organized by module prefix matching the page path.

## Directory Structure

Locale files mirror page paths, with special directories for components and layout:

```
web/locales/zh_CN/              (en_US mirrors exactly)
├── index.ts                    # Aggregates all modules
├── menu.ts                     # Standalone files (no page path prefix)
├── login.ts
├── dashboard/                  # dashboard/*.tsx pages
│   ├── analysis.ts
│   ├── monitor.ts
│   └── workplace.ts
├── system/                     # system/*.tsx pages
│   ├── info.ts
│   ├── user.ts
│   ├── rule.ts
│   └── ...
├── ai/                         # ai/*.tsx pages
│   ├── chat.ts
│   ├── conversation.ts
│   └── agent.ts
├── user/                       # user/*.tsx pages
│   └── profile.ts
├── components/                 # Shared component translations
│   ├── xin-form.ts
│   ├── xin-table.ts
│   └── xin-crud.ts
└── layout/                     # Layout translations
    └── layout.ts
```

## Key Naming Rules

### 1. Page translations follow page path

The key prefix equals the page file path (without extension, `/index` dropped):

| Page file | Locale file | Key prefix |
|-----------|-------------|------------|
| `pages/system/info.tsx` | `locales/zh_CN/system/info.ts` | `system.info` |
| `pages/system/user.tsx` | `locales/zh_CN/system/user.ts` | `system.user` |
| `pages/system/dict/index.tsx` | `locales/zh_CN/system/dict.ts` | `system.dict` |
| `pages/ai/chat/index.tsx` | `locales/zh_CN/ai/chat.ts` | `ai.chat` |
| `pages/dashboard/analysis.tsx` | `locales/zh_CN/dashboard/analysis.ts` | `dashboard.analysis` |

### 2. `index.tsx` pages drop "/index"

`pages/ai/chat/index.tsx` → file goes at `ai/chat.ts`, not `ai/chat/index.ts`.

### 3. Components go in `components/` directory

| Component | Locale file | Key prefix |
|-----------|-------------|------------|
| `XinForm` + sub-components | `components/xin-form.ts` | `xin.form` |
| `XinTable` + sub-components | `components/xin-table.ts` | `xin.table` |
| XinCrud shared keys | `components/xin-crud.ts` | `xin.crud` |

### 4. Layout goes in `layout/` directory

Layout translations are in `layout/layout.ts` with prefix `layout.*`.

### 5. Standalone files stay at root

Files without a page path prefix (like `menu.ts`, `login.ts`) stay at the root of the locale directory.

## File Format

```typescript
export default {
  // Section comment (Chinese in zh_CN, English in en_US)
  "prefix.page.title": "页面标题",
  "prefix.field.name": "字段名",
  "prefix.field.name.required": "字段名为必填项",
};
```

- **Quotes**: Always double quotes for keys and values
- **Indentation**: 2 spaces
- **Trailing commas**: Yes (after last entry)
- **Comments**: Section comments in the locale's own language
- **No `index.ts` files** inside subdirectories — only at the language root for aggregation

## Registering New Files

Import and spread new locale files in the language root `index.ts`:

```typescript
import moduleName from "./path/to/file";

export default {
  ...moduleName,
  // ... other modules
};
```

Both `zh_CN/index.ts` and `en_US/index.ts` must be updated identically.
