export interface IDict {
    /** ID */
    id?: number;
    /** 字典名称 */
    name?: string;
    /** 字典编码 */
    code?: string;
    /** 渲染类型：text纯文本,tag标签,badge徽标 */
    render_type?: 'text' | 'tag' | 'badge';
    /** 字典描述 */
    describe?: string;
    /** 状态：0正常 1停用 */
    status?: number;
    /** 排序 */
    sort?: number;
    /** 创建时间 */
    created_at?: string;
    /** 更新时间 */
    updated_at?: string;
}

/** 渲染类型选项 */
export const RENDER_TYPES = [
    { label: '纯文本', value: 'text' },
    { label: '标签', value: 'tag' },
    { label: '徽标', value: 'badge' },
] as const;
