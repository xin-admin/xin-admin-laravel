import React from 'react';
import {debounce} from 'lodash';
import {Button, Col, ColorPicker, Divider, Drawer, InputNumber, Row, Select, Switch, theme, Tooltip} from 'antd';
import { useGlobalStore } from "@/stores";
import {configTheme, darkColorTheme, defaultColorTheme} from "@/layout/theme.ts";
import {algorithmOptions} from "@/layout/algorithm.ts";

import {useTranslation} from 'react-i18next';
import {useThemeTransition} from '@/hooks/useThemeTransition';

const {useToken} = theme;

// 主题配置项
const THEME_CONFIGS = [
  {key: 'colorPrimary', label: 'layout.colorPrimary'},
  {key: 'colorText', label: 'layout.colorText'},
  {key: 'colorBg', label: 'layout.colorBg'},
  {key: 'colorSuccess', label: 'layout.colorSuccess'},
  {key: 'colorWarning', label: 'layout.colorWarning'},
  {key: 'colorError', label: 'layout.colorError'},
  {key: 'bodyBg', label: 'layout.bodyBg'},
  {key: 'footerBg', label: 'layout.footerBg'},
  {key: 'headerBg', label: 'layout.headerBg'},
  {key: 'headerColor', label: 'layout.headerColor'},
  {key: 'siderBg', label: 'layout.siderBg'},
  {key: 'siderColor', label: 'layout.siderColor'},
  {key: 'colorBorder', label: 'layout.colorBorder'},
];

// 风格配置项
const STYLE_CONFIGS = [
  {key: 'fixedFooter', label: 'layout.fixedFooter', type: 'switch'},
  {key: 'motion', label: 'layout.motion', type: 'switch'},
  {key: 'layoutBorder', label: 'layout.layoutBorder', type: 'switch'},
  {key: 'borderRadius', label: 'layout.borderRadius', type: 'number', min: 0, max: 30},
  {key: 'controlHeight', label: 'layout.controlHeight', type: 'number', min: 0},
  {key: 'headerPadding', label: 'layout.headerPadding', type: 'number', min: 0},
  {key: 'headerHeight', label: 'layout.headerHeight', type: 'number', min: 0},
  {key: 'siderWeight', label: 'layout.siderWeight', type: 'number', min: 0},
  {key: 'bodyPadding', label: 'layout.bodyPadding', type: 'number', min: 0},
];

// 预设主题列表
const THEME_LIST = [
  {background: '/theme/default.svg', name: 'light', title: 'layout.themeLight'},
  {background: '/theme/dark.svg', name: 'dark', title: 'layout.themeDark'},
];

// 布局配置
const LAYOUT_CONFIGS = [
  {
    key: 'side', title: 'layout.layoutSide', render: (token: any) => (
      <>
        <div className="rounded-sm w-full h-6 mb-1.5" style={{background: token.colorPrimaryBorder}}/>
        <div className="flex">
          <div className="rounded-sm h-16 w-6" style={{background: token.colorPrimary}}/>
          <div className="rounded-sm flex-1 ml-1.5" style={{background: token.colorPrimaryBg}}/>
        </div>
      </>
    )
  },
  {
    key: 'top', title: 'layout.layoutTop', render: (token: any) => (
      <>
        <div className="rounded-sm h-6 w-full mb-1.5" style={{background: token.colorPrimary}}/>
        <div className="rounded-sm h-16" style={{background: token.colorPrimaryBg}}/>
      </>
    )
  },
  {
    key: 'mix', title: 'layout.layoutMix', render: (token: any) => (
      <>
        <div className="rounded-sm w-full h-6 mb-1.5" style={{background: token.colorPrimary}}/>
        <div className="flex">
          <div className="rounded-sm h-16 w-6" style={{background: token.colorPrimary}}/>
          <div className="rounded-sm flex-1 ml-1.5" style={{background: token.colorPrimaryBg}}/>
        </div>
      </>
    )
  },
  {
    key: 'columns', title: 'layout.layoutColumns', render: (token: any) => (
      <div className="flex">
        <div className="rounded-sm mr-1.5 w-3 h-24" style={{background: token.colorPrimary}}/>
        <div className="rounded-sm mr-1.5 w-6 h-24" style={{background: token.colorPrimaryHover}}/>
        <div className="rounded-sm flex-auto h-24" style={{background: token.colorPrimaryBg}}/>
      </div>
    )
  },
];

const SettingDrawer: React.FC = () => {
  const {t} = useTranslation();
  const {token} = useToken();
  const themeDrawer = useGlobalStore(state => state.themeDrawer);
  const setThemeDrawer = useGlobalStore(state => state.setThemeDrawer);
  const setThemeConfig = useGlobalStore(state => state.setThemeConfig);
  const themeConfig = useGlobalStore(state => state.themeConfig);
  const layout = useGlobalStore(state => state.layout);
  const setLayout = useGlobalStore(state => state.setLayout);
  
  // 主题过渡动画 Hook
  const { transitionTheme, transitionThemeWithCircle, isTransitioning } = useThemeTransition();
  
  // 翻译后的算法选项
  const translatedAlgorithmOptions = algorithmOptions?.map(option => ({
    ...option,
    label: t(option.label as string)
  })) || [];

  // 防抖更新主题配置（带过渡动画）
  const changeSetting = debounce((key: string, value: any) => {
    transitionTheme(() => {
      setThemeConfig({...themeConfig, [key]: value});
    });
  }, 300, {leading: true, trailing: false});

  // 处理主题切换（带圆形扩散动画）
  const handleThemeChange = (e: React.MouseEvent<HTMLDivElement>) => {
    const target = (e.target as HTMLElement).closest('[data-theme]');
    if (!target) return;

    const themeName = (target as HTMLElement).dataset.theme;
    const themeMap = {
      dark: darkColorTheme,
      light: defaultColorTheme
    };

    if (themeMap[themeName as keyof typeof themeMap]) {
      // 使用圆形扩散动画切换主题
      transitionThemeWithCircle(e, () => {
        setThemeConfig({...themeConfig, ...themeMap[themeName as keyof typeof themeMap]});
      });
    }
  };

  // 重置主题（带过渡动画）
  const resetTheme = () => {
    transitionTheme(() => {
      setThemeConfig({...configTheme, ...defaultColorTheme});
    });
  };

  return (
    <Drawer
      styles={{body: {paddingTop: 10}}}
      placement="right"
      closable={false}
      onClose={() => setThemeDrawer(false)}
      open={themeDrawer}
      width={392}
      footer={(
        <div className="flex w-full justify-between">
          <Button onClick={resetTheme} disabled={isTransitioning}>
            {t('layout.resetTheme')}
          </Button>
          <Button type="primary" disabled={isTransitioning}>
            {t('layout.saveTheme')}
          </Button>
        </div>
      )}
    >
      {/* 布局样式 */}
      <Divider>{t('layout.layoutStyle')}</Divider>
      <Row gutter={[20, 20]}>
        {LAYOUT_CONFIGS.map(({key, title, render}) => (
          <Col span={12} key={key}>
            <Tooltip title={t(title)}>
              <div
                className="p-2 rounded-lg cursor-pointer"
                style={{
                  boxShadow: token.boxShadow,
                  borderRadius: token.borderRadius,
                  border: layout === key ? `2px solid ${token.colorPrimary}` : '2px solid transparent',
                }}
                onClick={() => setLayout(key as any)}
              >
                {render(token)}
              </div>
            </Tooltip>
          </Col>
        ))}
      </Row>

      {/* 预设主题 */}
      <Divider>{t('layout.presetTheme')}</Divider>
      <Row gutter={20} onClick={handleThemeChange}>
        {THEME_LIST.map((item) => (
          <Col span={8} key={item.name} className="mb-2.5">
            <div
              data-theme={item.name}
              className="cursor-pointer overflow-hidden border-solid"
              style={{
                borderRadius: token.borderRadius,
                borderWidth: themeConfig.themeScheme === item.name ? '2px' : '0px',
                borderColor: themeConfig.themeScheme === item.name ? token.colorPrimary : 'transparent'
              }}
            >
              <img src={item.background} alt={item.name}/>
            </div>
            <div className="text-center mt-1.5">{t(item.title)}</div>
          </Col>
        ))}
        <Col span={24} className="mb-2.5">
          <div className="flex justify-between items-center">
            <div>{t('layout.themeAlgorithm')}</div>
            <Select
              value={themeConfig.algorithm}
              style={{ width: 160 }}
              onChange={(value) => changeSetting('algorithm', value)}
              options={translatedAlgorithmOptions}
            />
          </div>
        </Col>
      </Row>

      {/* 主题颜色 */}
      <Divider>{t('layout.themeColor')}</Divider>
      {THEME_CONFIGS.map(({key, label}) => (
        <div key={key} className="flex justify-between items-center mb-2.5">
          <div>{t(label)}</div>
          <ColorPicker
            showText
            value={themeConfig[key as keyof typeof themeConfig] as string}
            onChange={(value) => changeSetting(key, value.toCssString())}
          />
        </div>
      ))}

      {/* 风格配置 */}
      <Divider>{t('layout.styleConfig')}</Divider>
      {STYLE_CONFIGS.map(({key, label, type, ...rest}) => (
        <div key={key} className="flex justify-between items-center mb-2.5">
          <div>{t(label)}</div>
          {type === 'switch' ? (
            <Switch
              value={themeConfig[key as keyof typeof themeConfig] as boolean}
              onChange={(value) => changeSetting(key, value)}
            />
          ) : (
            <InputNumber
              value={themeConfig[key as keyof typeof themeConfig] as number}
              onChange={(value) => changeSetting(key, value)}
              {...rest}
            />
          )}
        </div>
      ))}
    </Drawer>
  );
};

export default SettingDrawer;