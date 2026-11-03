import { Badge, Tag } from 'antd';
import React, { useMemo } from 'react';
import useDictStore from '@/stores/dict';

export interface DictTagProps {
  /** 字典编码 */
  code: string;
  /** 字典值 */
  value?: string | number;
  /** 渲染样式 */
  renderType?: 'text' | 'tag' | 'badge';
  /** 默认文本 */
  defaultText?: string;
  /** 默认颜色 */
  defaultColor?: string;
  /** 配置 */
}

const DictTag: React.FC<DictTagProps> = (props) => {
  const {
    code,
    value,
    renderType = 'text',
    defaultText = '-',
    defaultColor = 'default',
  } = props;

  const getDictItem = useDictStore((state) => state.getDictItem);

  const dictItem = useMemo(() => {
    return getDictItem(code, value);
  }, [code, value, getDictItem]);

  const label = dictItem?.label || defaultText;
  const color = dictItem?.color || defaultColor;

  if (renderType === 'text') {
    return <span>{label}</span>;
  }

  if (renderType === 'badge') {
    return <Badge color={color} text={label} />;
  }

  return <Tag color={color}>{label}</Tag>;
};

export default DictTag;
