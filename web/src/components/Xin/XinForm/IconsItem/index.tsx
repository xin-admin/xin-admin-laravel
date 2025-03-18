import { FormInstance, Input, Modal, Tabs, TabsProps, theme } from 'antd';
import React, { CSSProperties, useEffect, useState } from 'react';
import { categories, CategoriesKeys } from './fields';
import IconFont from '@/components/IconFont';
const { useToken } = theme;

export interface IconsItemProps {
  value?: any,
  form: FormInstance,
  dataIndex?: string | number | bigint
}

export default (props: IconsItemProps) => {
  const { value, form, dataIndex } = props;
  // 当前选中图标
  const [selected, setSelected] = useState<string>('');
  // 选择菜单显示
  const [iconShow, setIconShow] = useState<boolean>(false);
  const { token } = useToken();

  useEffect(() => {
    setSelected(form.getFieldValue(dataIndex));
  }, [form]);

  // 打开选择窗口
  const openModel = () => { setIconShow(true) }

  const IconListCss: CSSProperties = {
    display: 'flex',
    flexWrap: 'wrap',
    maxHeight: 300,
    overflow: 'auto',
  };

  const IconsList = (props: { type: CategoriesKeys }) => {
    return (
      <div style={IconListCss}>
        {categories[props.type].map((item) => (
          <div
            style={{
              padding: '5px 10px',
              marginRight: '10px',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              marginBottom: 8,
              borderWidth: 1,
              borderStyle: 'solid',
              borderColor: selected === item ? token.colorPrimary : token.colorBorder,
            }}
            key={item}
            onClick={() => setSelected(item)}
          >
            <IconFont name={item} />
          </div>
        ))}
      </div>
    );
  };

  const items: TabsProps['items'] = [
    {
      key: 'use',
      label: '自定义图标',
      children: <IconsList type={'useIcons'} />,
    },
    {
      key: 'suggestion',
      label: '网站通用图标',
      children: <IconsList type={'suggestionIcons'} />,
    },
    {
      key: 'direction',
      label: '方向性图标',
      children: <IconsList type={'directionIcons'} />,
    },
    {
      key: 'editor',
      label: '编辑类图标',
      children: <IconsList type={'editorIcons'} />,
    },
    {
      key: 'data',
      label: '数据类图标',
      children: <IconsList type={'dataIcons'} />,
    },
    {
      key: 'logo',
      label: '品牌和标识',
      children: <IconsList type={'logoIcons'} />,
    },
    {
      key: 'other',
      label: '其它图标',
      children: <IconsList type={'otherIcons'} />,
    },
  ];

  const onChange = () => {
    form.setFieldValue(dataIndex, selected);
    setIconShow(false);
  };

  return (
    <>
      <Input
        value={value}
        addonAfter={
          <>
            { categories.allIcons.includes(value) ?
              <span onClick={openModel}><IconFont name={value} /></span>
              :
              <span onClick={openModel}>请选择</span>
            }
          </>
        }
      />
      <Modal open={iconShow} onCancel={() => setIconShow(false)} width={800} onOk={onChange}>
        <Tabs defaultActiveKey="all" items={items} />
      </Modal>
    </>
  );
}
