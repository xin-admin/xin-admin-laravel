import {mock} from 'mockjs';
import XinTable from '@/components/XinTable';
import React, { useContext, useEffect, useState } from 'react';
import TableConfigContext from './TableConfigContext';
import { buildColumns } from './utils';
const api = '/online.onlineTable';
export default () => {
  const {tableConfig} = useContext(TableConfigContext)

  const [columns, setColumns] = useState([]);

  useEffect(() => {
    setColumns(buildColumns(tableConfig.columns))
  },[tableConfig])

  return (
      <XinTable<any>
        {...tableConfig.tableSetting}
        tableApi={api}
        tableStyle={{}}
        columns={columns}
        params={{ data: tableConfig.columns }}
        request={async (params) => {
          let data = params.data;
          let dataIndex = {};
          data.forEach((item: OnlineType.ColumnsConfig) => {
            // @ts-ignore
            dataIndex[item.dataIndex] = item.mock;
          });
          return mock({
            'data|5': [dataIndex],
            current_page: 1,
            last_page: 1,
            per_page: 100,
            total: 500,
          });
        }}
      />
  )
}
