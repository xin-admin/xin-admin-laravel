import { ProFormColumnsAndProColumns } from "@/components/XinTable/typings";
import XinTable from "@/components/XinTable";
import { Link } from '@umijs/max';
import { Access, useAccess } from "@umijs/max";
import { addApi } from "@/services/common/table";
import {
  defaultCRUDConfig,
  defaultTableSetting,
} from '@/pages/backend/Online/TableDevise/components/defaultData';

interface Data {
  id: number
  table_name: string
  update_time: string
  create_time: string
}

const api = '/online/online_table';

const OnlineTable = () => {
  const access = useAccess();

  const columns: ProFormColumnsAndProColumns<Data>[] = [
    {
      title: 'ID',
      dataIndex: 'id',
      hideInForm: true
    },
    {
      title: '表格名称',
      dataIndex: 'table_name',
      valueType: 'text',
    },
    {
      title: '数据表',
      dataIndex: 'data_table',
      valueType: 'text',
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
  return (
    <XinTable<Data>
      tableApi={api}
      columns={columns}
      handleAdd={async (data) => {
        let formData = {
          columns: JSON.stringify([]),
          crud_config: JSON.stringify(defaultCRUDConfig),
          table_config: JSON.stringify(defaultTableSetting),
          ...data
        }
        await addApi(api + '/add', Object.assign({}, formData))
        return true;
      }}
      operateRender={(record: Data) => {
        return (
          <Access accessible={access.buttonAccess('online.tableDevise')}>
            <Link to={'/online/tableDevise?id=' + record.id}>设计页面</Link>
          </Access>
        )
      }}
    />
  )

}


export default OnlineTable
