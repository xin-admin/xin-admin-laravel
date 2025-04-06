import {
  Button,
  Card,
  Checkbox,
  Form,
  FormInstance,
  Input,
  InputNumber,
  List,
  message,
  Popconfirm,
  Radio,
  Space,
  Switch,
  theme,
  Typography,
} from 'antd';
import { addApi, deleteApi, editApi, listApi } from '@/services/common/table';
import { BetaSchemaForm } from '@ant-design/pro-components';
import React, { useRef, useState } from 'react';
import { XinTableColumn } from '@/components/Xin/XinTable/typings';
import ButtonAccess from '@/components/Access/ButtonAccess';
import { DeleteOutlined, EditOutlined, PlusOutlined, ReloadOutlined } from '@ant-design/icons';
import { CardProps } from 'antd/es/card';
import { useAsyncEffect, useRequest } from 'ahooks';
import { ISettingGroup } from '@/domain/iSettingGroup';
import { ISetting } from '@/domain/iSetting';

const { Text } = Typography;

interface SettingGroupType {
  label: string;
  key: string;
  id: string;
  title: string;
}

// 设置项表单
export default () => {
  const token = theme.useToken();
  const [loading, setLoading] = useState(false);
  const [key, setKey] = useState<string>('web');
  const [group, setGroup] = useState<number>(1);
  const [dataSource, setDataSource] = useState();
  const formRef = useRef<FormInstance>();
  const [editId, setEditId] = useState<number>();
  const [formOpen, setFormOpen] = useState(false);
  const [settingGroup, setSettingGroup] = useState<SettingGroupType[]>([]);
  const [form] = Form.useForm();

  const columns: XinTableColumn<ISetting>[] = [
    {
      title: '设置标题',
      dataIndex: 'title',
      valueType: 'text',
      colProps: { span: 6 },
      formItemProps: { rules: [{ required: true, message: '设置标题必填' }] },
    },
    {
      title: '设置Key',
      dataIndex: 'key',
      valueType: 'text',
      formItemProps: { rules: [{ required: true, message: '设置Key必填' }] },
      tooltip: '推荐设置key格式为小写字母和下划线_',
      colProps: { span: 6 },
    },
    {
      title: '设置分组',
      dataIndex: 'group_id',
      valueType: 'select',
      formItemProps: { rules: [{ required: true, message: '设置分组必填' }] },
      fieldProps: {
        options: settingGroup,
        fieldNames: { label: 'label', value: 'id', },
      },
      colProps: { span: 6 },
    },
    {
      title: '设置类型',
      dataIndex: 'type',
      valueType: 'text',
      valueEnum: new Map([
        ['input', '输入框'],
        ['textarea', '文本域'],
        ['number', '数字输入框'],
        ['radio', '单选框'],
        ['checkout', '多选框'],
        ['switch', '开关'],
      ]),
      formItemProps: { rules: [{ required: true, message: '设置类型必填' }] },
      colProps: { span: 6 },
    },
    {
      title: '设置选项',
      dataIndex: 'options',
      valueType: 'textarea',
      tooltip: '当设置类型为单选框、多选、选择器的时候需要填入设置选项，label=key格式，以换行分割',
      colProps: { span: 12 },
    },
    {
      title: '表单项配置',
      dataIndex: 'props',
      valueType: 'textarea',
      tooltip: '表单项的配置，支持 Ant Design 所有非表达式的值，无需引号，比如：placeholder=Error 或 visibilityToggle=false, 以换行分割',
      colProps: { span: 12 },
    },
    {
      title: '默认值',
      dataIndex: 'values',
      valueType: 'text',
      tooltip: '默认的值',
      colProps: { span: 24 },
    },
    {
      title: '设置提示',
      dataIndex: 'describe',
      valueType: 'textarea',
      tooltip: '设置下方的提示信息',
      colProps: { span: 12 },
    },
    {
      title: '排序',
      dataIndex: 'sort',
      valueType: 'digit',
      tooltip: '数字越大越靠前',
      colProps: { span: 12 },
    },
  ];

  // 获取设置
  const querySetting = async (group_id = 1) => {
    let res = await listApi('/system/setting/query/' + group_id);
    setDataSource(res.data);
    let data: any = {};
    res.data.forEach((item: any) => {
      data[item.key] = item.values;
    });
    form?.setFieldsValue(data);
  };
  // 新增按钮点击事件
  const addButtonClick = () => {
    setEditId(undefined);
    setFormOpen(true);
    formRef.current?.resetFields();
  };
  // 编辑按钮点击事件
  const editButtonClick = (item: ISetting) => {
    setEditId(item.id);
    formRef.current?.setFieldsValue(item);
    setFormOpen(true);
  };
  // 删除按钮点击事件
  const deleteButtonClick = async (item: any) => {
    await deleteApi('/system/setting', { id: item.id });
    message.success('删除成功');
    await querySetting(item.group_id);
  };
  // 切换分组
  const menuClick: CardProps['onTabChange'] = (key) => {
    let data: any = settingGroup?.find((item: { key: string }) => {
      return item.key === key;
    });
    setKey(data.key);
    setGroup(data.id);
    querySetting(data.id).then(() => {});
  };
  // 保存设置
  const { run } = useRequest(async (data) => {
    await editApi('/system/setting/save/' + group, data);
    message.success('保存成功！');
  }, { debounceWait: 1000, manual: true });
  // 刷新设置缓存
  const refreshCache = async () => {
    await addApi('/system/setting/refreshCache');
    message.success('刷新成功');
  };
  useAsyncEffect(async () => {
    try {
      setLoading(true);
      let { data } = await listApi('/system/setting/group');
      setSettingGroup(data.data.map((item: ISettingGroup) => ({
        label: item.title,
        title: item.title,
        key: item.key,
        id: item.id,
      })))
      await querySetting();
      setLoading(false);
    } catch (e) {
      setLoading(false);
    }
  }, []);

  const listItemRender = (item: ISetting) => (
    <>
      <Space style={{ marginBottom: 5 }}>
        {item.title}
        <ButtonAccess auth={'system.setting.edit'}>
          <EditOutlined
            onClick={() => editButtonClick(item)}
            style={{ color: token.token.colorPrimary, cursor: 'pointer' }}
          />
        </ButtonAccess>
        <ButtonAccess auth={'system.setting.delete'}>
          <Popconfirm
            okText="Yes"
            cancelText="No"
            title="Delete the task"
            description="Are you sure to delete this task?"
            onConfirm={() => deleteButtonClick(item)}
          >
            <DeleteOutlined style={{ color: token.token.colorError }} />
          </Popconfirm>
        </ButtonAccess>
      </Space>
      <Form.Item name={item.key} style={{ marginBottom: 0 }}>
        {item.type === 'input' && <Input {...item.props} />}
        {item.type === 'textarea' && <Input.TextArea {...item.props} />}
        {item.type === 'checkout' && <Checkbox.Group {...item.props} options={item.options} />}
        {item.type === 'number' && <InputNumber {...item.props} min={1} max={10} defaultValue={3} />}
        {item.type === 'radio' && <Radio.Group {...item.props} options={item.options} />}
        {item.type === 'switch' && <Switch {...item.props} />}
      </Form.Item>
      <div style={{ marginBottom: 20 }}>
        <Text type="secondary" style={{ fontSize: 12 }}>{item.describe}，用法：</Text>
        <Text type="secondary" copyable>{'setting(\'' + key + '.' + item.key + '\')'}</Text>
      </div>
    </>
  );


  return (
    <>
      <BetaSchemaForm
        title={editId ? '编辑设置项' : '新增设置项'}
        formRef={formRef}
        layoutType={'ModalForm'}
        open={formOpen}
        modalProps={{ onCancel: () => setFormOpen(false), forceRender: true }}
        columns={columns}
        grid
        onFinish={async (formData: any) => {
          if (editId) {
            await editApi('/system/setting', { editId, ...formData });
            message.success('编辑成功');
          } else {
            await addApi('/system/setting', formData);
            message.success('添加成功');
          }
          await querySetting(formData.group_id);
          setFormOpen(false);
          formRef.current?.resetFields();
          return true;
        }}
      />
      <Card
        tabBarExtraContent={(
          <Space>
            <ButtonAccess auth={'system.setting.add'}>
              <Button
                onClick={addButtonClick}
                type={'primary'}
                icon={<PlusOutlined />}
                block
              >
                新增设置
              </Button>
            </ButtonAccess>
            <ButtonAccess auth={'system.setting.refresh'}>
              <Button
                onClick={refreshCache}
                type={'primary'}
                icon={<ReloadOutlined />}
                block
              >
                刷新缓存
              </Button>
            </ButtonAccess>
          </Space>
        )}
        tabList={settingGroup}
        onTabChange={menuClick}
        loading={loading}
        styles={{ body: { minHeight: 500 } }}
      >
        <Form form={form} onValuesChange={run}>
          <List
            pagination={false}
            key={'key'}
            dataSource={dataSource}
            renderItem={listItemRender}
          />
        </Form>
      </Card>
    </>
  );
}
