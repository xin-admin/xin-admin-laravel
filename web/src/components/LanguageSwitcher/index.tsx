import React, {useMemo} from 'react';
import {Button, Dropdown, type MenuProps} from 'antd';
import type { ButtonProps } from 'antd';
import { TranslationOutlined } from '@ant-design/icons';
import useLanguage from '@/hooks/useLanguage';

/**
 * 语言切换器组件
 */
const LanguageSwitcher: React.FC<ButtonProps> = (props) => {
  const { languageOptions, language, changeLanguage } = useLanguage();

  /**
   * 生成语言下拉菜单项
   */
  const languageMenuItems = useMemo<MenuProps['items']>(() => {
    return languageOptions.map((option) => ({
      key: option.value,
      label: option.label,
      onClick: () => changeLanguage(option.value),
    }));
  }, [languageOptions, changeLanguage]);

  return (
    <> 
			<Dropdown menu={{ items: languageMenuItems, selectedKeys: [language] }}>
				<Button icon={<TranslationOutlined />} {...props} />
			</Dropdown>
    </>
  );
};

export default LanguageSwitcher;
