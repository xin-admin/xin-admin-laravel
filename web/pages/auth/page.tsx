import {Alert, Card, Space, Tag, Radio} from "antd";
import useAuthStore from "@/stores/user";
import useMenuStore from "@/stores/menu";
import defaultRoute from "@/router/default.ts";

const PageAuth = () => {
  const access = useAuthStore(state => state.access)
  const setRules = useMenuStore(state => state.setMenus)

  return (
    <Card variant={"borderless"} title={'路由权限'}>
      <div className={'max-w-280'}>
        <Alert
          message="路由权限"
          description={(
            <>
              <a>XinAdmin UI</a> 中的权限具有灵活、可控、方便的优势。
              <a>XinAdmin UI</a> 将权限、路由、菜单统一组合为 <a>Rules</a> ，在开发过程中，只需要将 <a>Rules</a> 配置好传给前端，
              <a>XinAdmin UI</a> 就可以根据 <a>Rules</a> 自动解析权限、路由以及菜单。
            </>
          )}
          type="info"
          showIcon
          className={"mb-5"}
        />
        <Alert
          message="你可以点击下面切换角色来切换权限，注意观察权限字段与菜单信息！"
          type="info"
          showIcon
          className={"mb-5"}
        />

        <Radio.Group
          onChange={(e) => {
            const value = e.target.value;
            if (value === "A") {
              setRules(defaultRoute)
            }
            if (value === "B") {
              setRules(defaultRoute.filter(value => value.key !== 'page-layout'))
            }
            if (value === "C") {
              setRules(defaultRoute.filter(value => value.key !== 'multi-menu'))
            }
          }}
          defaultValue="A"
          style={{ display: 'flex', gap: 16 }}
        >
          <Radio.Button value="A" style={{ height: 'auto', padding: '16px 24px', display: 'flex', flexDirection: 'column', alignItems: 'flex-start' }}>
            <div style={{ fontWeight: 'bold', marginBottom: 8 }}>管理员</div>
            <div style={{ color: 'rgba(0,0,0,0.45)', whiteSpace: 'normal' }}>管理员拥有所有的权限</div>
          </Radio.Button>
          <Radio.Button value="B" style={{ height: 'auto', padding: '16px 24px', display: 'flex', flexDirection: 'column', alignItems: 'flex-start' }}>
            <div style={{ fontWeight: 'bold', marginBottom: 8 }}>运营人员</div>
            <div style={{ color: 'rgba(0,0,0,0.45)', whiteSpace: 'normal' }}>运营人员看不到 `页面布局` 菜单</div>
          </Radio.Button>
          <Radio.Button value="C" style={{ height: 'auto', padding: '16px 24px', display: 'flex', flexDirection: 'column', alignItems: 'flex-start' }}>
            <div style={{ fontWeight: 'bold', marginBottom: 8 }}>测试人员</div>
            <div style={{ color: 'rgba(0,0,0,0.45)', whiteSpace: 'normal' }}>测试人员看不到 `多级菜单` 菜单</div>
          </Radio.Button>
        </Radio.Group>

        <div className={"mb-2.5"}>当前拥有的权限:</div>
        <Space className={"mb-5"} wrap={true}>
          {access.map(item => <Tag>{item}</Tag>)}
        </Space>

        <Alert
          message="切换权限会重新刷新菜单和路由，页面会重新加载"
          type="warning"
          showIcon
          className={"mb-5"}
        />

      </div>
    </Card>
  )
}

export default PageAuth