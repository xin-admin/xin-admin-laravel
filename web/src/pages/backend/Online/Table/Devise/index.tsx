import { useNavigate, useSearchParams } from '@umijs/max';
import React, { useState } from 'react';
import './index.less';
import { Button, Card, Col, message, Row, Space, Tabs } from 'antd';
import { crudApi, getData, saveData } from '@/services/admin/online';
import TableSetting, { defaultTableSetting } from './components/TableSetting';
import ColumnsFrom from './components/ColumnsFrom';
import { buildColumns } from './components/utils';
import Preview from './components/Preview';
import CrudFrom from './components/CrudFrom';
import TableConfigContext from './components/TableConfigContext';
import DragSort from './components/DragSort';
import { useAsyncEffect } from 'ahooks';


export default () => {
  const nav = useNavigate();
  const [searchParams] = useSearchParams();
  const deviseId = searchParams.get('id');
  if (!deviseId) {
    nav('/online/table', { replace: true });
    return;
  }

  /**
   * 表格配置
   */
  const [tableConfig, setTableConfig] = useState<{
    tableSetting: OnlineType.TableConfig,
    crudConfig: OnlineType.CrudConfig,
    columns: OnlineType.ColumnsConfig[],
    id: string
  }>({
    tableSetting: defaultTableSetting,
    crudConfig: {
      name: 'TableName',
      controllerPath: '',
      modelPath: '',
      validatePath: '',
      pagePath: '',
    },
    columns: [],
    id: deviseId,
  });


  useAsyncEffect(async () => {
    let { data: { data: resData } } = await getData({ id: tableConfig.id });
    let columns: OnlineType.ColumnsConfig[];
    let table_config: OnlineType.TableConfig;
    let crud_config: OnlineType.CrudConfig;
    if (
      typeof resData.columns === 'string' &&
      typeof resData.table_config === 'string' &&
      typeof resData.crud_config === 'string'
    ) {
      try {
        columns = JSON.parse(resData.columns);
        table_config = JSON.parse(resData.table_config);
        crud_config = JSON.parse(resData.crud_config);
        setTableConfig({
          ...tableConfig,
          columns: buildColumns(columns),
          tableSetting: table_config,
          crudConfig: crud_config,
        });
      } catch (e) {
        message.warning('数据不是有效 JSON');
      }
    } else {
      message.warning('数据不是有效 JSON');
    }
  }, []);

  /**
   * 保存数据
   */
  const saveOnlineTable = () => {
    if (!tableConfig.id) {
      message.warning('在线开发ID不存在');
      return;
    }
    let data = {
      id: tableConfig.id,
      columns: JSON.stringify(tableConfig.columns),
      table_config: JSON.stringify(tableConfig.tableSetting),
      crud_config: JSON.stringify(tableConfig.crudConfig),
    };
    saveData(data).then(res => {
      if (res.success) {
        message.success('保存成功！');
      }
    });
  };

  /**
   * 保存并生成代码
   */
  const crud = () => {
    saveOnlineTable();
    let tableSetting: OnlineType.TableConfig = { ...tableConfig.tableSetting };
    delete tableSetting.paginationShow;
    delete tableSetting.searchShow;
    delete tableSetting.optionsShow;
    let data = {
      id: tableConfig.id,
      columns: tableConfig.columns,
      table_config: tableSetting,
      crud_config: tableConfig.crudConfig,
    };
    crudApi(data).then(res => {
      if (res.success) {
        message.success('代码生成成功！');
      }
    });
  };

  const [tabChange, setTableChange] = useState('1');
  const tabItem = [
    {
      key: '1',
      label: '表格配置',
      children: <TableSetting />,
    },
    {
      key: '2',
      label: '字段配置',
      children: <>
        <DragSort />
        <ColumnsFrom />
      </>,
    },
  ];

  return (
    <TableConfigContext.Provider value={{ tableConfig, setTableConfig }}>
      <Card
        title={'表格开发'}
        styles={{ body: { padding: 0 } }}
        extra={(
          <>
            <Space>
              <Button onClick={saveOnlineTable} type={'primary'}>保存编辑</Button>
              <Button onClick={crud} type={'primary'}>保存并生成代码</Button>
            </Space>
          </>
        )}
      >
        <Row>
          <Col span={6} style={{ padding: '10px 20px' }}>
            <Tabs
              defaultActiveKey='1'
              style={{ marginBottom: 32 }}
              items={tabItem}
              activeKey={tabChange}
              onChange={setTableChange}
            />
          </Col>
          <Col span={18} style={{ overflow: 'auto' }}>
            <div style={{ padding: '20px 20px', borderLeft: '1px solid #efefef' }}>
              <CrudFrom />
              <div style={{ marginTop: 10, display: 'flex', flexDirection: 'row-reverse' }}>

              </div>
            </div>
            <div style={{ padding: 20, background: '#efefef' }}>
              <Preview />
            </div>
          </Col>
        </Row>
      </Card>
    </TableConfigContext.Provider>
  );
};
