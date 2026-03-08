export interface IDictItem {
    /** ID */
    id?: number;
    /** 字典ID */
    dict_id?: number;
    /** 字典标签 */
    label?: string;
    /** 字典键值 */
    value?: string;
    /** 颜色 */
    color?: string;
    /** 状态：0正常 1停用 */
    status?: number;
    /** 排序 */
    sort?: number;
    /** 创建时间 */
    created_at?: string;
    /** 更新时间 */
    updated_at?: string;
}

/** Ant Design 颜色选项 */
export const COLORS = [
    { label: '默认', value: 'default' },
    { label: '蓝色', value: 'blue' },
    { label: '绿色', value: 'green' },
    { label: '红色', value: 'red' },
    { label: '橙色', value: 'orange' },
    { label: '紫色', value: 'purple' },
    { label: '青色', value: 'cyan' },
    { label: '金色', value: 'gold' },
    { label: '绿黄色', value: 'lime' },
    { label: '极客蓝', value: 'geekblue' },
    { label: '品红', value: 'magenta' },
    { label: '火山红', value: 'volcano' },
] as const;
