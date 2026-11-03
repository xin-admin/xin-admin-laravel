import type { ISysFileInfo } from '@/domain/iSysFile';
import type { ImgCropProps } from 'antd-img-crop';

/**
 * 图片上传器组件属性
 */
export interface ImageUploaderProps {
  /**
   * 上传地址
   */
  action: string;

  /**
   * 当前值
   * - 单选模式：ISysFileInfo | null
   * - 多选模式：ISysFileInfo[]
   */
  value?: ISysFileInfo | ISysFileInfo[] | null;

  /**
   * 上传完成回调
   * @param value ISysFileInfo | ISysFileInfo[] | null
   */
  onChange?: (value: ISysFileInfo | ISysFileInfo[] | null) => void;

  /**
   * 上传模式
   * @default 'single'
   */
  mode?: 'single' | 'multiple';

  /**
   * 是否禁用
   * @default false
   */
  disabled?: boolean;

  /**
   * 最大上传数量
   * @default 1 (单选模式) | 9 (多选模式)
   */
  maxCount?: number;

  /**
   * 图片宽度限制 (px)
   * @default 1920
   */
  maxWidth?: number;

  /**
   * 图片高度限制 (px)
   * @default 1080
   */
  maxHeight?: number;

  /**
   * 图片大小限制 (MB)
   * @default 5
   */
  maxSize?: number;

  /**
   * 是否支持裁剪
   * @default false
   */
  croppable?: boolean;

  /** 
   * 剪裁参数
   */
  cropperOptions?: Omit<ImgCropProps, 'children'>;

}