// 文件类型 10：图片，20：音频，30：视频，40：压缩包，50：其他
export type SysFileType = 10 | 20 | 30 | 40 | 50;

export interface ISysFileInfo {
  /** 文件ID */
  id?: number;
  /** 文件夹ID */
  group_id?: number;
  /** 上传渠道 10：系统用户，20：APP用户 */
  channel?: number;
  /** 存储磁盘 local/s3 */
  disk?: string;
  /** 文件类型 10：图片，20：音频，30：视频，40：压缩包，50：其他 */
  file_type?: SysFileType;
  /** 文件名 */
  file_name?: string;
  /** 文件路径 */
  file_path?: string;
  /** 预览URL */
  preview_url?: string;
  /** 文件访问URL */
  file_url?: string;
  /** 文件大小 */
  file_size?: number;
  /** 文件扩展名 */
  file_ext?: string;
  /** 上传用户ID */
  uploader_id?: number;
  /** 软删除时间 */
  deleted_at?: string;
  /** 创建时间 */
  created_at?: string;
  /** 更新时间 */
  updated_at?: string;
}