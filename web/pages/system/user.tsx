import {Avatar, Button, Form, Input, message, Modal, Tag, Tooltip, Typography} from 'antd';
import React, {useEffect, useMemo, useState} from 'react';
import XinTable from '@/components/XinTable';
import type {XinTableColumn, XinTableProps} from "@/components/XinTable/typings.ts";
import type ISysUser from "@/domain/iSysUser.ts";
import AuthButton from "@/components/AuthButton";
import type {DeptFieldType, ResetPasswordType, RoleFieldType} from "@/api/system/sys_user";
import {deptField, resetPassword, roleField} from "@/api/system/sys_user";
import {RedoOutlined} from "@ant-design/icons";
import {useTranslation} from "react-i18next";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import useAuth from "@/hooks/useAuth.ts";
const { Title, Text } = Typography;
dayjs.extend(relativeTime);

const Table: React.FC = () => {
  const {t} = useTranslation();
  const { auth } = useAuth();
  /** 角色选项数据 */
  const [roles, setRoles] = useState<RoleFieldType[]>([]);
  /** 部门选项数据 */
  const [departments, setDepartments] = useState<DeptFieldType[]>([]);
  /** 部门选项数据映射 */
  const departmentMap: Map<number, string> = useMemo(() => {
    const map: Map<number, string> = new Map();
    const buildMap = (dept: DeptFieldType[]) => {
      dept.forEach(item => {
        map.set(item.dept_id, item.name);
        if (item.children && item.children.length > 0) {
          buildMap(item.children);
        }
      })
    }
    buildMap(departments);
    return map;
  }, [departments]);

  /** 高级表格列配置 */
  const columns: XinTableColumn<ISysUser>[] = [
    {
      title: t("system.user.id"),
      dataIndex: 'id',
      hideInForm: true,
      sorter: true,
      align: 'center',
    },
    {
      title: t("system.user.username"),
      dataIndex: 'username',
      valueType: 'text',
      align: 'center',
      required: true,
      rules: [{required: true, message: t("system.user.username.required")}],
    },
    {
      title: t("system.user.nickname"),
      dataIndex: 'nickname',
      valueType: 'text',
      align: 'center',
      required: true,
      rules: [{required: true, message: t("system.user.nickname.required")}],
    },
    {
      title: t("system.user.sex"),
      dataIndex: 'sex',
      valueType: 'radio',
      filters: [
        { text: t("system.user.sex.0"), value: 0 },
        { text: t("system.user.sex.1"), value: 1 },
      ],
      align: 'center',
      hideInSearch: true,
      fieldProps: {
        options: [
          { value: 0, label: t("system.user.sex.0") },
          { value: 1, label: t("system.user.sex.1") },
        ]
      },
      render: (value: number) => {
        return value === 0 ? t("system.user.sex.0") : t("system.user.sex.1");
      }
    },
    {
      title: t("system.user.email"),
      dataIndex: 'email',
      valueType: 'text',
      align: 'center',
      required: true,
      rules: [{required: true, message: t("system.user.email.required")}],
    },
    {
      title: t("system.user.role"),
      dataIndex: 'role_id',
      valueType: 'select',
      align: 'center',
      hideInSearch: true,
      required: true,
      rules: [{required: true, message: t("system.user.role.required")}],
      render: (value: number[]) => (
        <>
          {value.map(item => (
            <Tag color={'magenta'}>
              {roles.find(r => r.role_id === item)?.name || '-'}
            </Tag>
          ))}
        </>
      ),
      fieldProps: {
        mode: 'multiple',
        options: roles.map(r => ({ label: r.name, value: r.role_id })),
      },
    },
    {
      title: t("system.user.dept"),
      dataIndex: 'dept_id',
      valueType: 'treeSelect',
      align: 'center',
      required: true,
      rules: [{required: true, message: t("system.user.dept.required")}],
      render: (value) => (
        // 嵌套寻找部门名称
        <Tag color={'volcano'}>{departmentMap.get(value) || '-'}</Tag>
      ),
      fieldProps: {
        treeData: departments,
        fieldNames: {label: 'name', value: 'dept_id'}
      }
    },
    {
      title: t("system.user.status"),
      dataIndex: 'status',
      valueType: 'radioButton',
      fieldProps: {
        options: [
          { value: 0, label: t("system.user.status.0") },
          { value: 1, label: t("system.user.status.1") },
        ]
      },
      render: (value: number) => {
        return value === 1
          ? <Tag color="success">{t("system.user.status.1")}</Tag>
          : <Tag color="error">{t("system.user.status.0")}</Tag>;
      },
      required: true,
      rules: [{required: true, message: t("system.user.status.required")}],
      filters: [
        { text: t("system.user.status.0"), value: 0 },
        { text: t("system.user.status.1"), value: 1 },
      ],
      hideInSearch: true,
      align: 'center',
    },
    {
      title: t("system.user.mobile"),
      dataIndex: 'mobile',
      valueType: 'text',
      required: true,
      rules: [{required: true, message: t("system.user.mobile.required")}],
      align: 'center',
    },
    {
      title: t("system.user.avatar"),
      dataIndex: 'avatar_url',
      hideInSearch: true,
      hideInForm: true,
      render: (_, entity: ISysUser) => <Avatar size={'small'} src={entity.avatar_url}></Avatar>,
      align: 'center',
    },
    {
      title: t("system.user.password"),
      dataIndex: 'password',
      valueType: 'password',
      hideInTable: true,
      hideInSearch: true,
      hideInUpdate: true,
      rules: [{required: true, message: t("system.user.password.required")}],
      fieldProps: {autoComplete: 'new-password'},
    },
    {
      title: t("system.user.rePassword"),
      dataIndex: 'rePassword',
      valueType: 'password',
      hideInTable: true,
      hideInSearch: true,
      hideInUpdate: true,
      rules: [{required: true, message: t("system.user.rePassword.required")}],
      fieldProps: {autoComplete: 'new-password'},
    },
    {
      title: t("system.user.created_at"),
      hideInForm: true,
      hideInSearch: true,
      dataIndex: 'created_at',
      align: 'center',
      render: (value: string) => value ? dayjs(value).fromNow() : '-',
    },
    {
      title: t("system.user.updated_at"),
      hideInForm: true,
      hideInSearch: true,
      dataIndex: 'updated_at',
      align: 'center',
      render: (value: string) => value ? dayjs(value).fromNow() : '-',
    },
  ];

  /** 修改密码相关状态 */
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [resetUserId, setResetUserId] = useState<number>();
  const [buttonLoading, setButtonLoading] = useState<boolean>(false);

  /** 打开重置密码框 */
  const showRedoModal = (id: number) => {
    setIsModalOpen(true);
    setResetUserId(id);
  };

  /** 提交重置密码 */
  const handleRedoSubmit = async (values: ResetPasswordType) => {
    try {
      setButtonLoading(true);
      await resetPassword({...values, id: resetUserId!});
      setIsModalOpen(false);
      message.success(t("system.user.resetSuccess"));
    } finally {
      setButtonLoading(false);
    }
  };

  useEffect(() => {
    if(auth('system.user.dept')) {
      deptField().then(res => setDepartments(res.data.data!));
    }
    if(auth('system.user.role')) {
      roleField().then(res => setRoles(res.data.data!));
    }
  }, []);

  /** 操作栏之后渲染 */
  const beforeOperateRender: XinTableProps['beforeOperateRender'] = (record) => (
    <>
      {record.id !== 1 &&
        <AuthButton auth={'system.user.resetPassword'}>
          <Tooltip title={t("system.user.resetPassword")}>
            <Button
              variant={'solid'}
              color={'pink'}
              icon={<RedoOutlined/>}
              size={'small'}
              onClick={() => showRedoModal(record.id!)}
            />
          </Tooltip>
        </AuthButton>
      }
    </>
  );

  /** 表格配置 */
  const tableProps: XinTableProps<ISysUser> = {
    api: '/system/user',
    columns,
    rowKey: 'id',
    accessName: 'system.user',
    beforeOperateRender,
    scroll: { x: 1400 },
    editShow: (i) => i.id !== 1,
    deleteShow: (i) => i.id !== 1,
    formProps: {
      grid: true,
      colProps: {span: 12},
      rowProps: {gutter: [30, 0]},
      layout: 'vertical'
    },
    modalProps: {
      width: 800,
    },
    cardProps: {
      variant: 'borderless'
    },
    pagination: {
      size: 'small',
        style: {
        marginBottom: 0
      }
    }
  }

  return (
    <>
      <div className={'mb-5'}>
        <Title level={3}>{t("system.user.page.title")}</Title>
        <Text type="secondary">{t("system.user.page.description")}</Text>
      </div>
      <XinTable<ISysUser> {...tableProps} />
      <Modal
        title={t("system.user.resetPassword")}
        closable={{'aria-label': 'Custom Close Button'}}
        open={isModalOpen}
        footer={null}
        styles={{body: {paddingTop: 20}}}
        onCancel={() => setIsModalOpen(false)}
      >
        <Form<ResetPasswordType> autoComplete="off" layout={'vertical'} onFinish={handleRedoSubmit}>
          <Form.Item
            label={t("system.user.password")}
            name="password"
            rules={[{required: true, message: t("system.user.password.required")}]}
          >
            <Input.Password/>
          </Form.Item>
          <Form.Item
            label={t("system.user.rePassword")}
            name="rePassword"
            rules={[{required: true, message: t("system.user.rePassword.required")}]}
          >
            <Input.Password/>
          </Form.Item>
          <Form.Item label={null} style={{marginTop: 30}}>
            <Button type="primary" block size={'large'} htmlType="submit" loading={buttonLoading}>
              {t("system.user.resetButton")}
            </Button>
          </Form.Item>
        </Form>
      </Modal>
    </>
  );
};

export default Table;
