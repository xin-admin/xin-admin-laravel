import XinTable from '@/components/XinTable'
import { ProFormColumnsAndProColumns } from '@/components/XinTable/typings';
import { Button } from 'antd';
import DictItem from "./components/DictItem";
import { useState } from "react";
import { useModel } from '@umijs/max';
import ButtonAccess from '@/components/ButtonAccess';

const api = '/system/dict';

interface Data {
  id?: number
  name?: string
  describe?: string
  create_time?: string
  code?: string
  update_time?: string
}

const columns: ProFormColumnsAndProColumns<Data>[] = [
  {
    title: 'ID',
    dataIndex: 'id',
    hideInForm: true,
    sorter: true
  },
  {
    title: '字典名称',
    dataIndex: 'name',
    valueType: 'text',
  },
  {
    title: '字典编码',
    dataIndex: 'code',
    valueType: 'text'
  },
  {
    title: '类型',
    dataIndex: 'type',
    valueType: 'select',
    valueEnum: {
      default: { text: '文字', status: 'Success' },
      badge: {
        text: '徽标',
        status: 'Success'
      },
      tag: {
        text: '标签',
        status: 'Success',
      },
    },
    filters: true
  },
  {
    title: '描述',
    dataIndex: 'describe',
    valueType: 'text',
    hideInSearch: true
  },
  {
    title: '创建时间',
    dataIndex: 'created_at',
    valueType: 'date',
    hideInForm: true
  },
  {
    title: '修改时间',
    dataIndex: 'updated_at',
    valueType: 'date',
    hideInForm: true
  },
];



const Table: React.FC = () => {
  const [open, setOpen] = useState(false);
  const [record, setRecord] = useState<Data>({})
  const { refreshDict } = useModel('dictModel');
  const onClose = () => setOpen(false);

  return (
    <>
      <DictItem open={open} onClose={onClose} dictData={record} />
      <XinTable<Data>
        tableApi={api}
        columns={columns}
        options={{
          density: true,
          search: true,
          fullScreen: true,
          setting: true,
        }}
        rowSelectionShow={false}
        optionsRender={(_, dom): React.ReactNode[] => {
          return [<Button type="primary" key={'ref'} onClick={() => { refreshDict() }}>刷新字典缓存</Button>, ...dom]
        }}
        operateRender={(record: Data) => {
          return (
            <ButtonAccess auth={'system.dict.item.list'}>
              <a onClick={() => {
                setRecord(record)
                setOpen(true)
              }}>字典配置</a>
            </ButtonAccess>
          )
        }}
        accessName={'system.dict'}
      />
    </>

  )

}

export default Table
