import { createFromIconfontCN, ExclamationOutlined } from '@ant-design/icons';
import * as AntdIcons from '@ant-design/icons';
import React from 'react';
import { categories } from '../XinForm/IconsItem/fields'

const allIcons: { [key: string]: any } = AntdIcons;

const OtherIcons = createFromIconfontCN({
  // 以下是默认值，也可以按需要指定
  scriptUrl: '//at.alicdn.com/t/c/font_4413039_6ow46w95lhw.js',
});

export default (props: {name?: string}) => {
  if (!props.name || !categories.allIcons.includes(props.name)) {
    return <ExclamationOutlined />
  } else if(allIcons[props.name]) {
    return React.createElement(allIcons[props.name])
  } else {
    return <OtherIcons type={props.name} className={props.name} />
  }
}
