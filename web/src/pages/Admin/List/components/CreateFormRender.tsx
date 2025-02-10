import { Button, message } from 'antd';
import { ActionType, BetaSchemaForm, ProFormColumnsType } from '@ant-design/pro-components';
import React from 'react';
import { AdminListType } from '../index';
import { addApi } from '@/services/common/table';

interface CreateFormRenderProps {
  columns: ProFormColumnsType[],
  actionRef: React.MutableRefObject<ActionType | undefined>,
}

const CreateFormRender = (props: CreateFormRenderProps, ) => {
  const { columns, actionRef } = props

  const onFinish = async (formData: AdminListType) => {
    const res = await addApi('/admin/list', Object.assign({},formData))
    if (res.success) {
      message.success('添加成功');
      actionRef.current?.reloadAndRest?.();
      return true
    }
    return false
  }

  return (
    <BetaSchemaForm<AdminListType>
      rowProps={{ gutter: [16, 16] }}
      title={'新增管理员'}
      trigger={ <Button type="primary">新增管理员</Button> }
      layoutType={'ModalForm'}
      colProps={{ span: 12 }}
      modalProps = {{ destroyOnClose: true }}
      initialValues={{}}
      onFinish={ onFinish }
      columns= { columns }
    />
  )
}

export default CreateFormRender;
