import { useBoolean } from 'ahooks';
import React, { useImperativeHandle, useRef, useState } from 'react';
import {
  ActionType,
  BetaSchemaForm,
  ProColumns,
  ProFormInstance,
  ProTable,
  ProTableProps,
} from '@ant-design/pro-components';
import { addApi, deleteApi, editApi, listApi } from '@/services/common/table';
import { Button, Divider, message, Popconfirm, Space } from 'antd';
import ButtonAccess from '@/components/Access/ButtonAccess';
import { XinTableProps } from '@/components/Xin/XinTable/typings';

export default function XinTable<T extends Record<string, any>>(props: XinTableProps<T>) {
  /** 表格参数 */
  const {
    api,
    rowKey,
    columns,
    tableRef,
    accessName,
    operateShow,
    editShow = true,
    addShow = true,
    deleteShow = true,
    beforeOperateRender,
    afterOperateRender,
    toolBarRender = [],
  } = props;
  /** 表格 Ref */
  const actionRef = useRef<ActionType>();
  /** 表单 Ref */
  const formRef = useRef<ProFormInstance>();
  /** 表单开启状态 */
  const [formOpen, setFormOpen] = useBoolean(false);
  /** 表单初始数据 */
  const [formInitValue, setFormInitValue] = useState<T | false>(false);
  /** Ref */
  useImperativeHandle(tableRef, () => ({
    tableRef: actionRef,
    formRef: formRef,
  }));
  /** 新增按钮点击事件 */
  const addButtonClick = () => {
    setFormInitValue(false);
    formRef.current?.resetFields();
    setFormOpen.setTrue();
  };
  /** 编辑按钮点击事件 */
  const editButtonClick = (record: T) => {
    setFormInitValue(record);
    setFormOpen.setTrue();
    formRef.current?.setFieldsValue(record);
  };
  /** 删除按钮点击事件 */
  const deleteButtonClick = async (record: T) => {
    await deleteApi(api, { [rowKey]: record[rowKey] });
    message.success('删除成功！');
    actionRef.current?.reset?.();
  };
  /** 提交表单 */
  const onFinish = async (formData: T) => {
    if (props.onFinish) {
      let ba = await props.onFinish(formData, formInitValue);
      if (ba) {
        setFormOpen.setFalse();
        actionRef.current?.reset?.();
      }
      return;
    }
    if (formInitValue && formInitValue[rowKey]) {
      let data: T = Object.assign(formInitValue, formData);
      await editApi(api, data);
    } else {
      await addApi(api, formData);
    }
    actionRef.current?.reset?.();
    message.success('提交成功');
    setFormOpen.setFalse();
  };
  /** 表格操作列 */
  const operate = (): ProColumns<T>[] => {
    if (operateShow === false) return [];
    return [
      {
        title: '操作',
        hideInForm: true,
        hideInSearch: true,
        key: 'operate',
        align: 'center',
        hideInDescriptions: true,
        render: (_, record) => (
          <Space split={<Divider type="vertical" />} size={0}>
            {beforeOperateRender?.(record)}
            {(typeof editShow === 'function' ? editShow(record) : editShow) &&
              <ButtonAccess auth={props.accessName + '.update'} key={'update'}>
                <a children={'编辑'} type={'link'} onClick={() => editButtonClick(record)} />
              </ButtonAccess>
            }
            {(typeof deleteShow === 'function' ? deleteShow(record) : deleteShow) !== false &&
              <ButtonAccess auth={props.accessName + '.delete'} key={'delete '}>
                <Popconfirm
                  okText="确认"
                  cancelText="取消"
                  title="Delete the task"
                  description="你确定要删除这条数据吗？"
                  onConfirm={() => deleteButtonClick(record)}
                >
                  <a>删除</a>
                </Popconfirm>
              </ButtonAccess>
            }
            {afterOperateRender?.(record)}
          </Space>
        ),
      },
    ];
  };
  /** 表格参数 */
  const tableProps: ProTableProps<T, any> = {
    actionRef,
    rowKey,
    columns: [...props.columns, ...operate()],
    toolBarRender: () => {
      return [
        <>
          {addShow &&
            <ButtonAccess auth={accessName + '.create'} key={'create'}>
              <Button children={'新增'} type={'primary'} onClick={addButtonClick} />
            </ButtonAccess>
          }
        </>,
        ...toolBarRender,
      ];
    },
    request: async (params, sorter, filter) => {
      const { data, success } = await listApi(props.api, { page: params.current,  sorter, filter, ...params });
      return { ...data, success };
    },
    ...props.tableProps,
  };
  return (
    <>
      <BetaSchemaForm<T>
        open={formOpen}
        layoutType={'ModalForm'}
        onFinish={onFinish}
        columns={columns}
        formRef={formRef}
        modalProps={{ onCancel: setFormOpen.setFalse, forceRender: true }}
        {...props.formProps}
      />
      <ProTable<T> {...tableProps} />
    </>
  );
}
