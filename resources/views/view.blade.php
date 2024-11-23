import React from "react";
import XinTable from '@/components/XinTable'
import {ProFormColumnsAndProColumns, TableProps} from '@/components/XinTable/typings';
import * as verify from '@/utils/format';

/**
*  Api 接口
*/
const api = '/{{$api}}'

/**
*  数据类型
*/
interface Data {
    [key: string] : any
}

/**
* 表格渲染
*/
const {{$name}}: React.FC = () => {

    {!! 'const columns: ProFormColumnsAndProColumns<Data>[] =' !!}
    {!! $columns !!}

    {!! 'const tableConfig: TableProps<Data> =' !!}
    {!! $table_config !!}

    {!! 'return <XinTable<Data> {...tableConfig}/>' !!}

}

export default {{$name}}
