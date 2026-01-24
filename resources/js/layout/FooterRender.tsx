import React from "react";
import {Layout} from "antd";
import {useGlobalStore} from "@/stores";

const {Footer} = Layout;

const FooterRender: React.FC = () => {

  const currentYear = new Date().getFullYear();
  const themeConfig = useGlobalStore(state => state.themeConfig);

  return (
    <>
      {themeConfig.fixedFooter &&
          <div className={"h-10"}></div>
      }
      <Footer
        className={
          (themeConfig.fixedFooter ? 'sticky' : 'relative') +
          " z-10 w-full bottom-0 pt-2.5 pb-2.5"
        }
        style={{
          borderTop: themeConfig.layoutBorder ? '1px solid ' + themeConfig.colorBorder : 'none',
        }}
      >
        <div className={"flex items-center justify-center w-full"}>
          Xin Admin ©{currentYear} Created by xiaoliu
        </div>
      </Footer>
    </>
  );
};

export default FooterRender;
