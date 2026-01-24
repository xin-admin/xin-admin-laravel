import {Button, Divider, Space} from "antd";
import HeaderRightRender from "@/layout/HeaderRightRender";
import BreadcrumbRender from "@/components/BreadcrumbRender";
import {MenuFoldOutlined, MenuUnfoldOutlined} from "@ant-design/icons";
import {useGlobalStore} from "@/stores";

const HeaderRender = () => {
  const collapsed = useGlobalStore(state => state.collapsed);
  const setCollapsed = useGlobalStore(state => state.setCollapsed);

  return (
    <div className={"flex justify-between items-center h-[56px]"}>
      <Space split={<Divider type="vertical" />} size={5}>
        <Button
          size={'small'}
          type={'text'}
          onClick={() => setCollapsed(!collapsed)}
        >
          { collapsed ? <MenuUnfoldOutlined/> : <MenuFoldOutlined/> }
        </Button>
        <BreadcrumbRender/>
      </Space>
      <HeaderRightRender/>
    </div>
  )
}

export default HeaderRender;