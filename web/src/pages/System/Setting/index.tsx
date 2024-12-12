import React, { useEffect, useState } from 'react';
import {
  Alert,
  Button,
  Card,
  Checkbox,
  ColorPicker,
  ConfigProvider,
  DatePicker,
  Form,
  Input,
  InputNumber,
  List,
  Menu,
  message,
  Popconfirm,
  Radio,
  Rate,
  Select,
  Slider,
  Space,
  Switch,
  theme,
  TimePicker,
  Typography,
} from 'antd';
import { getSettingGroup, saveSetting } from '@/services/system';
import { ProCard } from '@ant-design/pro-components';
import { deleteApi, listApi } from '@/services/common/table';
import AddSettingGroup from './components/AddSettingGroup';
import { DeleteOutlined, EditOutlined, PlusOutlined } from '@ant-design/icons';
import SettingForm from '@/pages/System/Setting/components/SettingForm';
import ButtonAccess from '@/components/ButtonAccess';

const { Text } = Typography;

export default () => {
  const [form] = Form.useForm();
  // 设置分组
  const [settingGroup, setSettingGroup] = useState([]);
  // 查询 Params
  const [key, setKey] = useState<string>('web');
  const [group, setGroup] = useState<number>(3);
  // 设置项
  const [dataSource, setDataSource] = useState();

  const token = theme.useToken();

  const getSetting = (group_id = 3) => {
    listApi('/system/setting/list', { group_id }).then((res) => {
      setDataSource(res.data);
      if (form) {
        let data: any = {};
        res.data.forEach((item: any) => {
          data[item.key] = item.values;
        });
        form.setFieldsValue(data);
      }
    });
  };

  useEffect(() => {
    getSettingGroup().then((res) => {
      setSettingGroup(res.data);
    });
    getSetting();
  }, []);

  return (
    <>
      <Alert message='系统设置可以方便快速的实现对后台可变参数的配置，php代码中直接粘贴用法即可获取到当前配置'
             type='success' style={{ marginBottom: 10 }} />
      <Card
        title={'系统设置'}
        styles={{
          body: { padding: 0, paddingTop: 1 },
        }}
        extra={
          <Space>
            <ButtonAccess auth={'admin.group.rule'}>
              <AddSettingGroup />
            </ButtonAccess>
            <SettingForm settingGroup={settingGroup} getSetting={getSetting}>
              <Button type={'primary'} icon={<PlusOutlined />} block>新增设置</Button>
            </SettingForm>
          </Space>
        }
      >
        <ProCard split='vertical'>
          <ProCard colSpan="160px" bodyStyle={{ padding: 0 }}>
            <ConfigProvider
              theme={{
                components: {
                  Menu: {
                    activeBarBorderWidth: 0,
                    itemBorderRadius: 0,
                    itemMarginInline: 0,
                  },
                },
              }}
            >
              <Menu
                onClick={(menu) => {
                  let data: { key: string, id: number }[] = settingGroup?.filter((item: { key: string }) => {
                    return item.key === menu.key;
                  });
                  setKey(data[0].key);
                  setGroup(data[0].id);
                  getSetting(data[0].id);
                }}
                defaultSelectedKeys={['web']}
                mode='inline'
                items={settingGroup}
              />
            </ConfigProvider>
          </ProCard>
          <ProCard style={{ minHeight: 500 }}>
            <Form form={form} onFinish={(values: any) => {
              saveSetting({group_id: group,...values}).then(() => {
                message.success('保存成功！');
              })
            }}>
              <List
                pagination={false}
                key={'key'}
                dataSource={dataSource}
                renderItem={(item: any) => (
                  <>
                    <Space style={{ marginBottom: 10 }}>
                      {item.title}
                      <SettingForm getSetting={getSetting} settingGroup={settingGroup} id={item.id}
                                   defaultData={item.defaultData}>
                        <EditOutlined style={{ color: token.token.colorPrimary }}></EditOutlined>
                      </SettingForm>
                      <Popconfirm
                        title='Delete the task'
                        description='Are you sure to delete this task?'
                        onConfirm={() => {
                          deleteApi('/system/setting/delete', { ids: item.id }).then(() => {
                            message.success('删除成功');
                            getSetting(item.group_id);
                          });
                        }}
                        okText='Yes'
                        cancelText='No'
                      >
                        <DeleteOutlined style={{ color: token.token.colorError }} />
                      </Popconfirm>
                    </Space>
                    <Form.Item name={item.key} style={{ maxWidth: 680, marginBottom: 0 }}>
                      {item.type === 'input' && <Input {...item.props} />}
                      {item.type === 'password' && <Input.Password {...item.props} />}
                      {item.type === 'textarea' && <Input.TextArea {...item.props} />}
                      {item.type === 'checkout' && <Checkbox.Group {...item.props} options={item.options} />}
                      {item.type === 'color' && <ColorPicker {...item.props} defaultValue='#1677ff' showText />}
                      {item.type === 'date' && <DatePicker {...item.props} />}
                      {item.type === 'number' && <InputNumber {...item.props} min={1} max={10} defaultValue={3} />}
                      {item.type === 'radio' && <Radio.Group {...item.props} options={item.options} />}
                      {item.type === 'rate' && <Rate {...item.props} allowHalf defaultValue={2.5} />}
                      {item.type === 'select' && <Select {...item.props} options={item.options} />}
                      {item.type === 'slider' && <Slider {...item.props} />}
                      {item.type === 'switch' && <Switch {...item.props} />}
                      {item.type === 'time' && <TimePicker {...item.props} />}
                    </Form.Item>
                    <div style={{ marginBottom: 20 }}>
                      <Text type='secondary' style={{ fontSize: 12 }}>{item.describe}，用法：</Text>
                      <Text type='secondary' copyable>{'get_setting(\'' + key + '.' + item.key + '\')'}</Text>
                    </div>
                  </>
                )}
              />
              <Button type={'primary'} htmlType='submit'>保存设置</Button>
            </Form>
          </ProCard>
        </ProCard>
      </Card>
    </>
  )
}
