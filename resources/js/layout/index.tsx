import {Layout} from "antd";
import HeaderRender from "@/layout/HeaderRender";
import MenuRender from "@/layout/MenuRender";
import SettingDrawer from "@/layout/SettingDrawer";
import PageTitle from "@/components/PageTitle";
import type {ReactNode} from "react";


const LayoutRender = (props: {children: ReactNode}) => {
  const {children} = props;
  return (
    <Layout className="min-h-screen" style={{backgroundColor: '#fff'}}>
      {/* 页面标题 */}
      <PageTitle/>

      {/* 主题设置抽屉 */}
      <SettingDrawer />

      <Layout hasSider>
        <MenuRender />
        <Layout className={"relative"}>
          <div className={'pl-2 pr-4 pb-4'}>
            <HeaderRender/>
            {children}
          </div>
        </Layout>
      </Layout>
    </Layout>
  );
};

export default LayoutRender;
