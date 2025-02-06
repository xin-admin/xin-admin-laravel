import XinTableV2 from '@/components/XinTableV2';
import { XinTableColumn } from '@/components/XinTableV2/typings';
import { IFileGroup } from '@/domain/iFileGroup';

export default () => {

  const columns: XinTableColumn<IFileGroup>[] = [
    { title: '分组ID', dataIndex: 'group_id', hideInForm: true, hideInSearch: true, sorter: true },
    { title: '分组名称', dataIndex: 'name', valueType: 'text' },
    { title: '分组排序', dataIndex: 'sort', valueType: 'digit', hideInSearch: true, },
    { title: '分组描述', dataIndex: 'describe', valueType: 'textarea', hideInSearch: true, },
    { title: '创建时间', dataIndex: 'created_at', valueType: 'dateTime', hideInSearch: true, hideInForm: true, },
    { title: '更新时间', dataIndex: 'updated_at', valueType: 'dateTime', hideInSearch: true, hideInForm: true, },
  ]

  return (
    <XinTableV2<IFileGroup>
      api={'/file/group'}
      columns={columns}
      accessName={'file.group'}
      rowKey={'group_id'}
      deleteShow={(record) => record.group_id !== 0}
      tableProps={{
        headerTitle: '文件分组列表'
      }}
    />
  )
}
