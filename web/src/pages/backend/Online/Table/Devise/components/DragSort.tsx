import type { ProColumns } from '@ant-design/pro-components';
import { DragSortTable } from '@ant-design/pro-components';
import { message } from 'antd';
import { useContext } from 'react';
import TableConfigContext from './TableConfigContext';

const columns: ProColumns[] = [
  {
    title: '排序',
    dataIndex: 'sort',
    width: 60,
    className: 'drag-visible',
  },
  {
    title: '字段名',
    dataIndex: 'dataIndex',
  },
  {
    title: '字段备注',
    dataIndex: 'title',
  }
];

export default () => {
  const {tableConfig,setTableConfig} = useContext(TableConfigContext);

  const handleDragSortEnd = (
    beforeIndex: number,
    afterIndex: number,
    newDataSource: any,
  ) => {
    setTableConfig({
      ...tableConfig,
      columns: newDataSource
    })
    message.success('修改列表排序成功');
  };

  return (
    <DragSortTable
      headerTitle={null}
      columns={columns}
      options={false}
      search={false}
      rowKey="key"
      pagination={false}
      dataSource={tableConfig.columns}
      dragSortKey="sort"
      cardProps={{
        bodyStyle: {padding: 0}
      }}
      onDragSortEnd={handleDragSortEnd}
    />
  );
};
