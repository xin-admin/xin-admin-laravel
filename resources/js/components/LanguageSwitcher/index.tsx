import React from 'react';
import { Button, Dropdown } from 'antd';
import type { ButtonProps } from 'antd';
import { TranslationOutlined } from '@ant-design/icons';
import { useLanguage } from '@/hooks/useLanguage';

/**
 * 语言切换器组件
 * 提供统一的语言切换下拉菜单
 */
const LanguageSwitcher: React.FC<ButtonProps> = (props) => {
  const { getLanguageMenuItems, language } = useLanguage();

  return (
    <> 
			<Dropdown menu={{ items: getLanguageMenuItems(), selectedKeys: [language] }}>
				<Button icon={<TranslationOutlined />} {...props} />
			</Dropdown>
    </>
  );
};

export default LanguageSwitcher;
