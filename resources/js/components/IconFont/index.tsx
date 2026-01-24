import { createFromIconfontCN, ExclamationOutlined } from '@ant-design/icons';
import * as AntdIcons from '@ant-design/icons';
import React from 'react';
import {categories, oauthScriptUrl} from '@/utils/iconFields.ts'

const allIcons: any = AntdIcons;

const OtherIcons = createFromIconfontCN({
  scriptUrl: oauthScriptUrl,
});

const IconFont = (props: {name?: string, style?: React.CSSProperties}) => {
  if (!props.name || !categories.allIcons.includes(props.name)) {
    return <ExclamationOutlined style={props.style} />
  } else if(allIcons[props.name]) {
    return React.createElement(allIcons[props.name] , {style: props.style})
  } else {
    return <OtherIcons type={props.name} className={props.name} style={props.style} />
  }
}

export default IconFont;