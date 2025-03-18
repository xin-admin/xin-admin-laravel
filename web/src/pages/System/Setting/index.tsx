import React, { useEffect, useState } from 'react';
import { Button, Card, Col, Form, List, message, Popconfirm, Row, Space, theme, Typography } from 'antd';
import { FormProps } from '@ant-design/pro-components';
import { deleteApi, editApi, listApi } from '@/services/common/table';
import { DeleteOutlined, EditOutlined, PlusOutlined } from '@ant-design/icons';
import SettingForm from '@/pages/System/Setting/components/SettingForm';
import ButtonAccess from '@/components/access/ButtonAccess';
import SettingItemRender from '@/pages/System/Setting/components/SettingItemRender';
import { CardProps } from 'antd/es/card';
import XinTable from '@/components/XinTable';

const { Text } = Typography;

export default () => {
  // 表单
  const [form] = Form.useForm();
  // 设置分组
  const [settingGroup, setSettingGroup] = useState([]);
  // 查询 Params
  const [key, setKey] = useState<string>('web');
  // 分组
  const [group, setGroup] = useState<number>(3);
  // 设置项
  const [dataSource, setDataSource] = useState();
  // token
  const token = theme.useToken();
  // 获取设置列表
  const getSetting = (group_id = 1) => {
    setLoading(true);
    listApi('/system/setting/query/' + group_id).then((res) => {
      setDataSource(res.data);
      if (form) {
        let data: any = {};
        res.data.forEach((item: any) => {
          data[item.key] = item.values;
        });
        form.setFieldsValue(data);
      }
    }).finally(() => {
      setLoading(false);
    });
  };

  useEffect(() => {
    listApi('/system/setting/group').then((res) => {
      if (res.data && res.data.data) {
        setSettingGroup(res.data.data.map((item: any) => {
          return {
            label: item.title,
            title: item.title,
            key: item.key,
            id: item.id,
          };
        }));
      }
    });
    getSetting();
  }, []);

  // 新增按钮
  const cardExtra = (
    <ButtonAccess auth={'system.setting.add'}>
      <SettingForm settingGroup={settingGroup} getSetting={getSetting}>
        <Button type={'primary'} icon={<PlusOutlined />} block>新增设置</Button>
      </SettingForm>
    </ButtonAccess>
  );

  // 菜单点击事件
  const menuClick: CardProps['onTabChange'] = (key) => {
    let data: any = settingGroup?.find((item: { key: string }) => {
      return item.key === key;
    });
    setKey(data.key);
    setGroup(data.id);
    getSetting(data.id);
  };

  // 表单保存事件
  const onFinish: FormProps['onFinish'] = async (values) => {
    await editApi('/system/setting/save/' + group, values);
    message.success('保存成功！');
  };

  // 删除设置
  const deleteSetting = async (item: any) => {
    await deleteApi('/system/setting', { id: item.id });
    message.success('删除成功');
    getSetting(item.group_id);
  };

  const settingItemRender = (item: any) => (
    <>
      <Space style={{ marginBottom: 5 }}>
        {item.title}
        <SettingForm getSetting={getSetting} settingGroup={settingGroup} id={item.id} defaultData={item.defaultData}>
          <EditOutlined style={{ color: token.token.colorPrimary }}></EditOutlined>
        </SettingForm>
        <Popconfirm okText="Yes" cancelText="No" title="Delete the task" description="Are you sure to delete this task?"
                    onConfirm={() => deleteSetting(item)}>
          <DeleteOutlined style={{ color: token.token.colorError }} />
        </Popconfirm>
      </Space>
      <SettingItemRender item={item} />
      <div style={{ marginBottom: 20 }}>
        <Text type="secondary" style={{ fontSize: 12 }}>{item.describe}，用法：</Text>
        <Text type="secondary" copyable>{'get_setting(\'' + key + '.' + item.key + '\')'}</Text>
      </div>
    </>
  );

  const [loading, setLoading] = useState(false);

  return (
    <Row gutter={20}>
      <Col span={14}>
        <Card
          tabBarExtraContent={cardExtra}
          tabList={settingGroup}
          onTabChange={menuClick}
          loading={loading}
          styles={{ body: { minHeight: 500 } }}
        >
          <Form form={form} onFinish={onFinish}>
            <List
              pagination={false}
              key={'key'}
              dataSource={dataSource}
              renderItem={settingItemRender}
            />
            <Button type={'primary'} htmlType="submit">保存设置</Button>
          </Form>
        </Card>
      </Col>
      <Col span={10}>
        <XinTable
          api={'/system/setting/group'}
          rowKey={'id'}
          accessName={'system.setting.group'}
          columns={[
            { title: '分组ID', dataIndex: 'id', hideInForm: true, sorter: true, align: 'center', },
            { title: 'KEY', dataIndex: 'key', valueType: 'text' },
            { title: '分组标题', dataIndex: 'title', valueType: 'text' },
            { title: '分组描述', dataIndex: 'remark', hideInTable: true, valueType: 'textarea' },
            { title: '创建时间', dataIndex: 'created_at', hideInForm: true, valueType: 'fromNow', },
            { title: '更新时间', dataIndex: 'updated_at', hideInForm: true, valueType: 'fromNow', }
          ]}
          tableProps={{ search: false, headerTitle: '设置分组', cardProps: { bordered: true } }}
        />
      </Col>
    </Row>
  );
}
