import {Alert, Button, Card, Flex, Space, Tag} from "antd";
import useAuthStore from "@/stores/user";
import ButtonAccess from "@/components/AuthButton";

const ButtonAuth = () => {
  const access = useAuthStore(state => state.access)
  const setAccess = useAuthStore(state => state.setAccess)

  const deleteCreateAccess = () => {
    setAccess(access.filter(item => item !== 'auth.button.create'))
  }

  const addCreateAccess = () => {
    if(! access.find(item => item === 'auth.button.create')) {
      setAccess([...access, 'auth.button.create'])
    }
  }

  return (
    <Card variant={"borderless"} title={"按钮权限"}>

      <div className={"max-w-280"}>
        <Alert
          message="按钮权限"
          description={(
            <>
              <a>XinAdmin UI</a> 中的按钮权限取于 <a>Rules</a> 中的 key 字段，约定 key 字段必须，并且每一条菜单、路由或者是按钮权限都拥有 key
              <a>XinAdmin UI</a> 会自动获取 <a>Rules</a> 中的 key 作为按钮权限的标识，如果拥有此 key 即可使用该操作。
            </>
          )}
          type="info"
          showIcon
          className={"mb-5"}
        />


        <div className={"mb-2.5"}>当前拥有的权限:</div>
        <Space className={"mb-5"} wrap={true}>
          { access.map(item => <Tag>{item}</Tag>)}
        </Space>

        <Flex gap={"small"} wrap className={"mb-5"}>
          <Button color={'danger'} variant="solid" onClick={deleteCreateAccess}>删除新增权限</Button>
          <Button color={'primary'} variant="solid" onClick={addCreateAccess}>添加新增权限</Button>
        </Flex>

        <div className={"mb-2.5"}>下面的按钮拥有相应的权限才可以看到:</div>
        <Flex gap="small" wrap className={"mb-5"}>
          <ButtonAccess auth={'auth.button.create'}>
            <Button color={'primary'} variant="solid">新增按钮 auth.button.create</Button>
          </ButtonAccess>
          <ButtonAccess auth={'auth.button.update'}>
            <Button color={'cyan'} variant="solid">编辑按钮 auth.button.update</Button>
          </ButtonAccess>
          <ButtonAccess auth={'auth.button.delete'}>
            <Button color={'pink'} variant="solid">删除按钮 auth.button.delete</Button>
          </ButtonAccess>
          <ButtonAccess auth={'auth.button.query'}>
            <Button color={'purple'} variant="solid">查询按钮 auth.button.query</Button>
          </ButtonAccess>
        </Flex>

        <Alert
          message="切换权限会重新刷新菜单和路由，页面会重新加载"
          type="warning"
          showIcon
          className={"mb-5"}
        />
      </div>
    </Card>
  );
};

export default ButtonAuth;