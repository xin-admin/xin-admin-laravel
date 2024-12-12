import {
  ModalForm,
  ProForm,
  ProFormMoney,
  ProFormSelect, ProFormTextArea,
} from '@ant-design/pro-components';
import { Avatar, Button, message, Space, Statistic, Tag } from 'antd';
import { useState } from 'react';
import { vagueSearchUser } from '@/services/user';
import { UserOutlined } from '@ant-design/icons';

export default () => {
  const [search,setSearch] = useState<string>();
  const [searchUser, setSearchUser] = useState<USER.UserInfo>();
  const [upMoney, setUpMoney] = useState<number | null>(0);
  const request = async () => {
    let {data} = await vagueSearchUser({search: search});
    return data.data.map((item) => {
      return {
        label: (
          <Space>
            <Avatar src={item.avatar} style={{ backgroundColor: '#87d068' }} icon={<UserOutlined />} size={24}/>
            <Tag icon={<UserOutlined />} color="geekblue">ID：{item.id}</Tag>
            {item.username?<Tag color="purple">Name：{item.username}</Tag>: ''}
            {item.mobile?<Tag color="magenta">Mobile：{item.mobile}</Tag>: ''}
          </Space>
        ),
        value: item.id,
        data: item
      }
    })
  }

  return (
    <>
      <ModalForm
        trigger={<Button type="primary">修改用户余额</Button>}
        onFinish={async (formData: {id:number,money:number,describe: string}) => {
          message.success('更新成功！')
          return true
        }}
      >
        <ProForm.Group >
          <ProFormSelect
            width="xl"
            name="id"
            label="选择用户（id，用户名，手机号模糊搜索）"
            rules={[{ required: true, message: '请选择用户!' }]}
            debounceTime={2}
            params={{search:search}}
            fieldProps={{
              showSearch: true,
              onSearch: (value) => {
                setSearch(value)
              },
              filterOption: false,
              allowClear: true,
              onSelect: (value, option) => {
                console.log(option.data)
                setSearchUser(option.data)
              }
            }}
            request={request}
          />
          <ProFormMoney
            width="md"
            name="money"
            label="变更金额"
            fieldProps={{
              onChange: (value) => {
                setUpMoney(value)
              }
            }}
            placeholder="请输入变更金额"
            rules={[{ required: true, message: '请输入变动金额!' }]}
          />
          <Statistic
            title="用户余额"
            value={searchUser?.money}
            precision={2}
            suffix="￥"
          />
          <Statistic
            title="修改后余额"
            value={searchUser && searchUser.money && upMoney ? Number(searchUser.money) + upMoney : 0 }
            precision={2}
            suffix="￥"
          />
          <ProFormTextArea
            width={'xl'}
            name="remark"
            label="备注"
            rules={[{ required: true, message: '请输入管理员备注' }]}
            placeholder="请输入备注"
          />
        </ProForm.Group>

      </ModalForm>
    </>
  );
};
