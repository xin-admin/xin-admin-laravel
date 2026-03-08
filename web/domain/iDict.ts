export interface IDict {
    /** ID */
    id?: number;
    /** 字典名称 */
    name?: string;
    /** 字典编码 */
    code?: string;
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
