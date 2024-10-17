import XinTable from '@/components/XinTable';
import { ProFormColumnsAndProColumns } from '@/components/XinTable/typings';
import React, { useEffect, useRef, useState } from 'react';
import { useBoolean } from 'ahooks';
import GroupRule from './components/GroupRule';
import { Access, useAccess } from '@umijs/max';
import * as tableApi from '@/services/common/table';
import { message, Popconfirm } from 'antd';
import { deleteApi } from '@/services/common/table';
import { ActionType } from '@ant-design/pro-components';

const api = '/user/group';

interface GroupListType {
  id: number;
  name: string;
  pid: string;
  rules: number[];
  create_time: string;
  update_time: string;
}

const Table: React.FC = () => {

  const [ref, setRef] = useBoolean();
  const [treeData, setTreeData] = useState<any[]>([]);
  useEffect(() => {
    tableApi.listApi('/user/rule/list').then(res => {
      setTreeData(res.data.data)
    })
  }, [])
  const actionRef = useRef<ActionType>()


  const formPid = ({ type }: any): ProFormColumnsAndProColumns<GroupListType>[] => {
    return type !== '0'
      ? [{
        title: '父节点',
        dataIndex: 'pid',
        valueType: 'treeSelect',
        initialValue: 1,
        params: { ref },
        fieldProps: {
          fieldNames: {
            label: 'name',
            value: 'id',
          },
        },
        request: async () => {
          let res = await tableApi.listApi(api + '/list');
          return res.data.data;
        },
        formItemProps: {
          rules: [
            { required: true, message: '此项为必填项' },
          ],
        },
      }] : []
  }
  const columns: ProFormColumnsAndProColumns<GroupListType>[] = [
    {
      title: '类型',
      dataIndex: 'type',
      valueType: 'radio',
      hideInTable: true,
      initialValue: '0',
      formItemProps: {
        rules: [
          { required: true, message: '此项为必填项' },
        ],
      },
      fieldProps: {
        options: [
          {
            label: '根节点',
            value: '0',
          },
          {
            label: '子节点',
            value: '1',
          },
        ],
      },
    },
    {
      title: 'ID',
      dataIndex: 'id',
      hideInForm: true,
      hideInTable: true
    },
    {
      title: '分组名',
      dataIndex: 'name',
      valueType: 'text',
    },
    {
      valueType: 'dependency',
      name: ['type'],
      hideInTable: true,
      columns: formPid
    },
    {
      title: '创建时间',
      dataIndex: 'create_time',
      valueType: 'date',
      hideInForm: true
    },
    {
      title: '编辑时间',
      dataIndex: 'update_time',
      valueType: 'date',
      hideInForm: true,
    },
  ];
  const access = useAccess();

  /**
   *  删除节点
   * @param selectedRows
   */
  const handleRemove = async (selectedRows: GroupListType[]) => {
    const hide = message.loading('正在删除');
    let ids = selectedRows.map(x => x.id)
    deleteApi(tableApi + '/delete', { ids: ids.join() || '' }).then(res => {
      if (res.success) {
        message.success(res.msg);
        actionRef.current?.reloadAndRest?.();
      } else {
        message.warning(res.msg);
      }
    }).finally(() => hide())
  }

  return (
    <>
      <XinTable<GroupListType>
        tableApi={api}
        columns={columns}
        search={false}
        accessName={'user.group'}
        addBefore={() => setRef.toggle()}
        deleteShow={false}
        actionRef={actionRef}
        expandable={{
          defaultExpandedRowKeys: []
        }}
        operateRender={(data) =>
          <>
            {data.id !== 1 && <>
              <Access accessible={access.buttonAccess('user.group.rule')}>
                <GroupRule record={data} treeData={treeData}></GroupRule>
              </Access>
              <Access accessible={access.buttonAccess('user.group.delete')}>
                <Popconfirm
                  title="Delete the task"
                  description="你确定要删除这条数据吗？"
                  onConfirm={() => { handleRemove([data]) }}
                  okText="确认"
                  cancelText="取消"
                >
                  <a>删除</a>
                </Popconfirm>
              </Access>
            </>}
          </>
        }
      />
    </>
  )

}

export default Table
