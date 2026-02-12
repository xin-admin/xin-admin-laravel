export default interface ISysLoginRecord {
    /** 记录ID */
    id?: number;
    /** 浏览器 */
    browser?: string;
    /** IP 地址 */
    ipaddr?: string;
    /** 登录地址 */
    login_location?: string;
    /** 登录时间 */
    login_time?: string;
    /** 登录提示 */
    msg?: string;
    /** 登录设备 */
    os?: string;
    /** 登陆状态 */
    status?: string;
    /** 用户ID */
    user_id?: number;
    /** 用户名 */
    username?: string;
}
