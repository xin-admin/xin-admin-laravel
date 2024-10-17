import type {MenuDataItem} from "@ant-design/pro-components";
import React from "react";
import {Link} from "@umijs/max";
import {Space} from "antd";
import {FormattedMessage} from "@@/exports";

const menuItemRender = (itemProps: MenuDataItem) => {
  if (itemProps.isUrl) {
    return (
      <a href={itemProps.path ? itemProps.path : ''}  target='_blank' rel="noreferrer">
        <Space>
          {itemProps.icon}
          {itemProps.local ? <FormattedMessage id={itemProps.local} /> : itemProps.name}
        </Space>
      </a>
    )
  }
  return (
    <Link to={itemProps.path ? itemProps.path : ''} target={itemProps.target}>
      <Space>
        {itemProps.icon}
        {itemProps.local ? <FormattedMessage id={itemProps.local} /> : itemProps.name}
      </Space>
    </Link>
  )
}

export default menuItemRender;
