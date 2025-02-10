import { useNavigate, useSearchParams } from '@umijs/max';
import React, { useState } from 'react';
import { Button, Card, ConfigProvider, message, Space, Tabs } from 'antd';
import { crudApi } from '@/services/online';
import { useAsyncEffect } from 'ahooks';
import { editApi, listApi } from '@/services/common/table';
import { defaultTableConfig } from './components/defaultData';
import ColumnsForm from '@/pages/Online/TableDevise/components/ColumnsForm';
import TableSettingFrom from '@/pages/Online/TableDevise/components/TableSettingFrom';
import CrudForm from '@/pages/Online/TableDevise/components/CrudForm';

export default () => {
  const nav = useNavigate();
  const [searchParams] = useSearchParams();
  const [tabChange, setTabChange] = useState('1');
  const [tableConfig, setTableConfig] = useState<OnlineType.OnlineTableType>(defaultTableConfig);

  // 初始化
  useAsyncEffect(async () => {
    const deviseId = searchParams.get('id');
    if (!deviseId) {
      nav('/online/table', { replace: true });
      return;
    }
    let resData = await listApi('/online/online_table/list', { id: deviseId });
    let onlineTableData: any;
    if (Array.isArray(resData.data.data) && resData.data.data.length > 0) {
      onlineTableData = resData.data.data[0];
    } else {
      nav('/online/table', { replace: true });
      return;
    }
    let columns: OnlineType.ColumnsConfig[];
    let table_config: OnlineType.TableConfig;
    let crud_config: OnlineType.CrudConfig;
    if (
      typeof onlineTableData.columns === 'string' &&
      typeof onlineTableData.table_config === 'string' &&
      typeof onlineTableData.crud_config === 'string'
    ) {
      try {
        columns = JSON.parse(onlineTableData.columns);
        table_config = JSON.parse(onlineTableData.table_config);
        crud_config = JSON.parse(onlineTableData.crud_config);
        setTableConfig({
          id: deviseId,
          columns: columns,
          tableSetting: table_config,
          crudConfig: crud_config,
        });
      } catch (e) {
        message.warning('数据不是有效 JSON');
        console.log(e);
      }
    } else {
      message.warning('数据不是有效 JSON 字符串');
    }
  }, []);

  // 保存数据
  const saveOnlineTable = async () => {
    let data = {
      id: tableConfig.id,
      columns: JSON.stringify(tableConfig.columns),
      table_config: JSON.stringify(tableConfig.tableSetting),
      crud_config: JSON.stringify(tableConfig.crudConfig),
    };
    await editApi('/online/online_table/edit', data);
    message.success('保存成功！');
  };

  // 保存并生成代码
  const crud = async () => {
    await saveOnlineTable();
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
    await crudApi(data);
    message.success('代码生成成功！');
  };

  const tabItem = [
    {
      key: '1',
      label: '表格配置',
    },
    {
      key: '2',
      label: '字段配置'
    },
    {
      key: '3',
      label: '生成配置'
    },
  ];

  return (
    <ConfigProvider theme={{
      components: {
        Form: { inlineItemMarginBottom: 8 },
      },
    }}>
      <Card
        defaultActiveTabKey='1'
        onTabChange={setTabChange}
        tabList={tabItem}
        activeTabKey={tabChange}
        tabBarExtraContent={(
          <>
            <Space>
              <Button onClick={saveOnlineTable} type={'primary'}>保存编辑</Button>
              <Button onClick={crud} type={'primary'}>保存并生成代码</Button>
            </Space>
          </>
        )}
      >
        { tabChange === '1' && <TableSettingFrom setTableConfig={setTableConfig} tableConfig={tableConfig}></TableSettingFrom> }
        { tabChange === '2' && <ColumnsForm tableConfig={tableConfig} setTableConfig={setTableConfig}></ColumnsForm> }
        { tabChange === '3' && <CrudForm tableConfig={tableConfig} setTableConfig={setTableConfig}></CrudForm> }
      </Card>
    </ConfigProvider>
  );
};
