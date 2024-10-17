import type {MenuDataItem} from "@ant-design/pro-components";
import {Space} from "antd";
import {FormattedMessage} from "@@/exports";

const subMenuItemRender = (itemProps: MenuDataItem) => {
  return (
    <Space>
      {itemProps.icon}
      {itemProps.local ? <FormattedMessage id={itemProps.local} /> : itemProps.name}
    </Space>
  )
}

export default subMenuItemRender;
