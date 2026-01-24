import {
  Alert,
  Button,
  Card,
  type CardProps,
  Col,
  message,
  Popconfirm,
  Row,
  Space, Table, type TableProps, Tag,
  Tree,
  type TreeDataNode, type TreeProps
} from "antd";
import {BankOutlined, DeleteOutlined, PlusOutlined, TeamOutlined, UserOutlined} from "@ant-design/icons";
import {useEffect, useRef, useState} from "react";
import {listDept, addDept, updateDept, deleteDept, deptUsers} from "@/api/sys/sysUserDept";
import type {ISysDept} from "@/domain/iSysDept.ts";
import {isArray, omit} from 'lodash';
import type {FormColumn} from "@/components/XinFormField/FieldRender/typings";
import XinForm, {type XinFormRef} from "@/components/XinForm";
import * as React from "react";
import {useTranslation} from "react-i18next";
import AuthButton from "@/components/AuthButton";
import useAuth from "@/hooks/useAuth";
import type ISysUser from "@/domain/iSysUser.ts";

const deptMap = new Map<string, ISysDept>();

interface TableParams {
  page: number;
  pageSize: number;
  total: number;
}

const Dept = () => {
  const {t} = useTranslation();
  const {auth} = useAuth();
  /** 部门信息表单 */
  const formRef = useRef<XinFormRef<ISysDept>>(undefined);
  /** 新增表单 */
  const modalFormRef = useRef<XinFormRef<ISysDept>>(undefined);
  /** 当前选中的部门 key */
  const [selectKey, setSelectKey] = useState<string>('');
  /** 当前（多选框）选择的部门 keys */
  const [checkedKeys , setCheckedKeys] = useState<React.Key[]>([]);
  /** 标签页 KEY */
  const [tabKey, setTabKey] = useState<string>('info');
  /** 标签页 Tab */
  const tabList: CardProps['tabList'] = [
    {
      key: 'info',
      label: t("sysUserDept.tab.info")
    },
    {
      key: 'users',
      label: t("sysUserDept.tab.users"),
      disabled: !auth("sys-user.dept.users")
    },
  ];
  /** 部门数据 */
  const [deptData, setDeptData] = useState<TreeDataNode[]>([]);
  /** 部门用户列表数据 */
  const [users, setUsers] = useState<ISysUser[]>([]);
  /** 部门用户列表表格参数 */
  const [tableParams, setTableParams] = useState<TableParams>({ page: 1, pageSize: 10, total: 0 });
  /** 加载状态 */
  const [loading, setLoading] = useState<boolean>(false);
  const [tableLoading, setTableLoading] = useState<boolean>(false);
  /** 获取部门用户列表 */
  const selectUsers = async (id: number, params?: TableParams) => {
    try {
      setTableLoading(true);
      const data = await deptUsers(id, omit(params || tableParams, 'total'));
      setUsers(data.data.data!.data);
      setTableParams({
        ...tableParams,
        total: data.data.data!.total
      })
    } finally {
      setTableLoading(false);
    }
  }
  /** 刷新部门数据 */
  const refreshDept = async () => {
    setLoading(true);
    /** 转换部门类型 */
    const convertDeptToTreeData = (depts: ISysDept[]): TreeDataNode[] => {
      if (!depts?.length) return [];
      return depts.map(dept => {
        deptMap.set(dept.id!.toString(), omit(dept, 'children'));
        return {
          title: dept.name || t("sysUserDept.tab.users"),
          key: dept.id?.toString() || '',
          icon: dept.type === 0 ? <BankOutlined /> : dept.type === 1 ? <TeamOutlined /> : <UserOutlined />,
          children: convertDeptToTreeData(dept.children || [])
        }
      });
    };
    try {
      const res = await listDept();
      if(res.data.data && res.data.data.length > 0) {
        deptMap.clear();
        setDeptData(convertDeptToTreeData(res.data.data));
        if(!selectKey || !deptMap.has(selectKey)) {
          setSelectKey(res.data.data[0].id!.toString());
          formRef.current?.setFieldsValue(omit(res.data.data[0], 'children'));
          if(auth("sys-user.dept.users")) {
            await selectUsers(res.data.data[0].id!);
          }
        }
      }
    } finally {
      setLoading(false);
    }
  };
  /** 新增部门点击事件 */
  const addChange = (children: boolean = false) => {
    modalFormRef.current?.resetFields();
    modalFormRef.current?.setFieldsValue({
      parent_id: children ? Number(selectKey) : 0,
      sort: 0,
      status: 0,
      type: 0
    })
    modalFormRef.current?.open();
  }
  /** 部门选择事件 */
  const onSelect: TreeProps['onSelect'] = (key) => {
    if(key && key.length >= 1) {
      setSelectKey(key[0].toString());
      const deptInfo = deptMap.get(key[0].toString());
      if (deptInfo) {
        formRef.current?.setFieldsValue(deptInfo);
      }
      selectUsers(Number(key[0])).then();
    }
  }
  /** 部门多选框选中事件 */
  const onCheck: TreeProps['onCheck'] = (checkedKeys) => {
    if(isArray(checkedKeys)) {
      setCheckedKeys(checkedKeys);
    }else {
      setCheckedKeys(checkedKeys.checked);
    }
  }
  /** 表单提交 */
  const onSubmit = async (data: ISysDept, update: boolean = false) => {
    try {
      setLoading(true);
      if(!update) {
        await addDept(data);
        message.success(t("sysUserDept.createSuccess"));
        modalFormRef.current?.close();
      }else {
        await updateDept(Number(selectKey), data);
        message.success(t("sysUserDept.updateSuccess"));
      }
      await refreshDept();
    }finally {
      setLoading(false);
    }
  }
  /** 批量删除 */
  const onDeleteConfirm = async () => {
    try {
      setLoading(true);
      await deleteDept(checkedKeys);
      await refreshDept();
      setCheckedKeys([]);
      message.success(t("sysUserDept.deleteSuccess"));
    }finally {
      setLoading(false);
    }
  }
  /** 表单列数据 */
  const columns: FormColumn<ISysDept>[] = [
    {
      title: t("sysUserDept.column.name"),
      valueType: 'text',
      dataIndex: 'name',
      rules: [{required: true, message: t("sysUserDept.column.name.required")}],
    },
    {
      title: t("sysUserDept.column.code"),
      valueType: 'text',
      dataIndex: 'code',
      rules: [{required: true, message: t("sysUserDept.column.code.required")}],
    },
    {
      title: t("sysUserDept.column.type"),
      valueType: 'radioButton',
      dataIndex: 'type',
      fieldProps: {
        options: [
          { value: 0, label: t("sysUserDept.column.type.0") },
          { value: 1, label: t("sysUserDept.column.type.1") },
          { value: 2, label: t("sysUserDept.column.type.2") },
        ],
      },
      rules: [{required: true, message: t("sysUserDept.column.type.required")}],
    },
    {
      title: t("sysUserDept.column.parent"),
      valueType: 'treeSelect',
      dataIndex: 'parent_id',
      fieldProps: {
        treeData: [
          {
            title: t("sysUserDept.column.parent.0"),
            value: 0,
            children: deptData
          }
        ],
        fieldNames: { label: 'title', value: 'key' },
        disabled: true
      },
      rules: [{required: true, message: t("sysUserDept.column.parent.required")}],
    },
    {
      title: t("sysUserDept.column.email"),
      valueType: 'text',
      dataIndex: 'email',
    },
    {
      title: t("sysUserDept.column.address"),
      valueType: 'text',
      dataIndex: 'address',
    },
    {
      title: t("sysUserDept.column.phone"),
      valueType: 'text',
      dataIndex: 'phone',
    },
    {
      title: t("sysUserDept.column.sort"),
      valueType: 'digit',
      dataIndex: 'sort',
      rules: [{required: true, message: t("sysUserDept.column.sort.required")}],
    },
    {
      title: t("sysUserDept.column.status"),
      valueType: 'radioButton',
      dataIndex: 'status',
      fieldProps: {
        options: [
          { value: 0, label: t("sysUserDept.column.status.0") },
          { value: 1, label: t("sysUserDept.column.status.1") },
        ]
      },
      rules: [{required: true, message: t("sysUserDept.column.status.required")}],
    },
    {
      title: t("sysUserDept.column.remark"),
      valueType: 'textarea',
      dataIndex: 'remark',
    },
  ];
  /** 部门用户列表表格列 */
  const usersColumns: TableProps<ISysUser>['columns'] = [
    {
      title: t("sysUserDept.users.column.id"),
      dataIndex: 'id',
      key: 'id',
      align: 'center',
    },
    {
      title: t("sysUserDept.users.column.username"),
      dataIndex: 'username',
      key: 'username',
      align: 'center',
    },
    {
      title: t("sysUserDept.users.column.nickname"),
      dataIndex: 'nickname',
      key: 'nickname',
      align: 'center',
    },
    {
      title: t("sysUserDept.users.column.nickname"),
      dataIndex: 'email',
      key: 'email',
      align: 'center',
    },
    {
      title: t("sysUserDept.users.column.mobile"),
      dataIndex: 'mobile',
      key: 'mobile',
      align: 'center',
    },
    {
      title: t("sysUserDept.users.column.status"),
      dataIndex: 'status',
      key: 'status',
      align: 'center',
      render: (value) => (
        <>
          {value === 1 && <Tag color={'success'}>{t("sysUserDept.users.column.status.0")}</Tag>}
          {value === 0 && <Tag color={'error'}>{t("sysUserDept.users.column.status.1")}</Tag>}
        </>
      )
    },
  ];
  /** 初始化 */
  useEffect(() => { refreshDept() }, []);

  return (
    <Row gutter={[20, 20]}>
      <Col xxl={12} lg={12} xs={24}>
        <XinForm<ISysDept>
          layoutType={'ModalForm'}
          formRef={modalFormRef}
          modalProps={{
            title: t("sysUserDept.createModalTitle"),
            styles: { body: { paddingTop: 20, paddingRight: 40 } },
          }}
          onFinish={async (data: ISysDept) => {
            await onSubmit(data, false);
            return true;
          }}
          columns={columns}
          layout={'horizontal'}
        />
        <Card
          title={(
            <Space>
              <AuthButton auth={"sys-user.dept.create"}>
                <Button
                  loading={loading}
                  children={t("sysUserDept.createButton")}
                  icon={<PlusOutlined />}
                  type={'primary'}
                  onClick={() => addChange()}
                />
              </AuthButton>
              <AuthButton auth={"sys-user.dept.create"}>
                <Button
                  loading={loading}
                  children={t("sysUserDept.createChildrenButton")}
                  icon={<PlusOutlined />}
                  type={'primary'}
                  onClick={() => addChange(true)}
                />
              </AuthButton>
            </Space>
          )}
          loading={loading}
          styles={{ body: { minHeight: '70vh' } }}
        >
          {checkedKeys.length > 0 && (
            <Alert
              style={{ marginBottom: 20 }}
              message={t("sysUserDept.checkedMessage", {checked: checkedKeys.length})}
              type="info"
              action={
                <Space>
                  <Button size="small" type="primary" onClick={()=> setCheckedKeys([])}>
                    {t("sysUserDept.unselect")}
                  </Button>
                  <AuthButton auth={"sys-user.dept.delete"}>
                    <Popconfirm
                      okText={t("sysUserDept.delete.ok")}
                      cancelText={t("sysUserDept.delete.cancel")}
                      title={t("sysUserDept.delete.title")}
                      description={t("sysUserDept.delete.description")}
                      onConfirm={() => onDeleteConfirm()}
                    >
                      <Button type="primary" icon={<DeleteOutlined />} size={'small'} danger loading={loading}/>
                    </Popconfirm>
                  </AuthButton>
                </Space>
              }
            />
          )}
          <Tree
            checkable
            treeData={deptData}
            showIcon={true}
            checkStrictly={true}
            selectedKeys={[selectKey]}
            defaultExpandedKeys={[selectKey]}
            onSelect={onSelect}
            checkedKeys={checkedKeys}
            onCheck={onCheck}
          />
        </Card>
      </Col>
      <Col xxl={12} lg={12} xs={24}>
        <Card
          tabList={tabList}
          tabProps={{ accessKey: tabKey }}
          onTabChange={setTabKey}
          styles={{ body: { minHeight: '70vh' } }}
        >
          <XinForm<ISysDept>
            formRef={formRef}
            onFinish={async (data: ISysDept) => {
              await onSubmit(data, true);
              return true;
            }}
            columns={columns}
            layout={'horizontal'}
            style={{ display: tabKey === 'info' ? "block" : "none" }}
            submitter={{
              render: (dom) => (
                <AuthButton auth={"sys-user.dept.update"}>
                  {dom['submit']}
                </AuthButton>
              ),
              submitText: t("sysUserDept.saveInfo")
            }}
          />
          <AuthButton auth={"sys-user.dept.users"}>
            <Table<ISysUser>
              style={{ display: tabKey === 'users' ? "block" : "none" }}
              dataSource={users}
              bordered={true}
              columns={usersColumns}
              loading={tableLoading}
              size={'small'}
              pagination={{
                current: tableParams.page,
                pageSize: tableParams.pageSize,
                total: tableParams.total,
                showSizeChanger: true,
                onChange: (page, pageSize) => {
                  const params = { total: tableParams.total, pageSize, page }
                  setTableParams(params);
                  selectUsers(Number(selectKey), params).then();
                }
              }}
              scroll={{x: 600}}
            />
          </AuthButton>
        </Card>
      </Col>
    </Row>
  );
};

export default Dept;