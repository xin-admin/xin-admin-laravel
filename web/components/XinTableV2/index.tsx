import {
  Button,
  Tree,
  Card,
  Flex,
  Form,
  Input,
  Space,
  Table,
  Tooltip,
  Popover,
  Dropdown,
  Typography,
  type TableProps,
  type TableColumnType,
  type TreeProps,
} from "antd";
import type {XinTableV2Props, XinTableV2Ref, RequestParams, FormMode} from "./typings";
import SearchForm from "./SearchForm";
import XinForm, {type XinFormRef} from "@/components/XinForm";
import {useCallback, useEffect, useImperativeHandle, useMemo, useRef, useState} from "react";
import type {FormColumn} from "@/components/XinFormField/FieldRender/typings";
import {Delete, List, Create, Update} from "@/api/common/table.ts";
import {isArray, isEmpty, omit} from "lodash";
import type {SearchProps} from "antd/es/input";
import {
  BorderlessTableOutlined,
  BorderOutlined,
  ColumnHeightOutlined,
  DeleteOutlined,
  EditOutlined,
  ReloadOutlined, SettingOutlined
} from "@ant-design/icons";
import {useTranslation} from "react-i18next";
import type {DataNode} from "antd/es/tree";
import AuthButton from "@/components/AuthButton";

// 默认分页大小
const DEFAULT_PAGE_SIZE: number = 10;
// 默认页数
const DEFAULT_PAGE: number = 1;

export default function XinTableV2<T extends Record<string, any> = any>(props: XinTableV2Props<T>) {
  const {
    api,
    accessName,
    rowKey,
    columns,

    formProps,
    modalProps,
    cardProps,
    searchProps,
    operateProps,

    tableRef,
    addShow = true,
    editShow = true,
    deleteShow = true,
    searchShow = true,
    operateShow = true,
    paginationShow = true,
    keywordSearchShow = true,
    titleRender,
    toolBarRender = [],

    beforeOperateRender,
    afterOperateRender,
    pagination: customPagination = {},
    handleRequest: customHandleRequest,
    requestParams: customRequestParams,
    handleFinish: customHandleFinish,
  } = props;

  const {t} = useTranslation();
  const formRef = useRef<XinFormRef<T>>(undefined);
  const [loading, setLoading] = useState<boolean>(true);
  const [searchRef] = Form.useForm<T>();
  const [dataSource, setDataSource] = useState<T[]>([]);
  const [total, setTotal] = useState<number>(0);
  const [requestParams, setRequestParams] = useState<RequestParams>({
    page: DEFAULT_PAGE,
    pageSize: DEFAULT_PAGE_SIZE,
  });
  // 操作栏状态
  const [density, setDensity] = useState<TableProps['size']>();
  const [bordered, setBordered] = useState<boolean>();
  const [columnsChecked, setColumnsChecked] = useState<any[]>([]);
  const [columnSorted, setColumnSorted] = useState<any[]>([]);
  
  // 表单模式状态
  const [formMode, setFormMode] = useState<FormMode>('create');
  const [formDefaultValues, setFormDefaultValues] = useState<T | undefined>(undefined);

  // 暴露表单方法
  useImperativeHandle(tableRef, (): XinTableV2Ref => ({
    /** 刷新表格（保持当前页） */
    reload: () => { handleRequest() },
    /** 获取当前数据源 */
    getDataSource: () => dataSource,
    /** 表单 ref */
    form: () => formRef.current!,
    /** 搜索表单 ref */
    searchForm: () => searchRef,
  }));

  /** 表格请求 */
  const handleRequest = async (params?: RequestParams) => {
    try {
      setLoading(true);
      const defaultParams: RequestParams = Object.assign({
        page: DEFAULT_PAGE,
        pageSize: DEFAULT_PAGE_SIZE,
      }, params)
      // 自定义参数处理
      const requestParams: RequestParams = customRequestParams ? customRequestParams(defaultParams) : defaultParams;
      // 自定义请求
      let listData: { data: T[]; total: number };
      if (customHandleRequest) {
        listData = await customHandleRequest(requestParams);
      } else {
        const { data } = await List<T>(api, requestParams);
        listData = data.data!;
      }
      setDataSource(listData.data);
      setTotal(listData.total);
    } finally {
      setLoading(false);
    }
  };

  /** 初始化 */
  useEffect(() => { handleRequest() }, []);

  /** 处理表格变化 */
  const handleTableChange: TableProps<T>['onChange'] = async (newPagination, newFilters, newSorter) => {
    console.log(newPagination, newFilters, newSorter)
    const params: RequestParams = {
      ...requestParams,
      page: newPagination.current ?? requestParams.page,
      pageSize: newPagination.pageSize ?? requestParams.pageSize,
    };
    // 处理筛选
    if (!isEmpty(newFilters)) {
      params.filterValues = newFilters;
    }
    // 处理排序
    if (newSorter && !isArray(newSorter) && !isEmpty(newSorter) && newSorter.field) {
      params.sorterValue = {
        field: String(newSorter.field),
        order: newSorter.order === 'ascend' ? 'asc' : 'desc',
      };
    } else {
      delete params.sorterValue;
    }
    setRequestParams(params);
    await handleRequest(params);
  };

  /** 快速搜索 */
  const handleKeywordSearch: SearchProps['onSearch'] = async (value: string) => {
    if( !value ) {
      window.$message?.warning(t('xinTableV2.keywordEmpty'));
      return;
    }
    const params: RequestParams = {
      ...requestParams,
      page: 1,
      keywordSearch: value,
    }
    setRequestParams(params);
    await handleRequest(params);
  };

  /** 快速搜索 Change */
  const keywordSearchChange: SearchProps['onChange'] = (e) => {
    if( !e.target.value ) {
      const { keywordSearch, ...params } = requestParams;
      setRequestParams(params);
      console.log('keywordSearch: ' + keywordSearch);
    } else {
      setRequestParams({
        ...requestParams,
        keywordSearch: e.target.value,
      });
    }
  };

  /** 搜索表单提交 */
  const handleSearch = async () => {
    const searchValues: T = searchRef.getFieldsValue();
    // 移除 空值
    Object.keys(searchValues).forEach((key) => {
      if (searchValues[key] === '' || searchValues[key] === undefined) {
        delete searchValues[key];
      }
    });
    await handleRequest({
      page: 1,
      ...requestParams,
      ...searchValues,
    });
  };

  /** 搜索列 */
  const searchColumn: FormColumn<T>[] = useMemo(() => {
    if(!searchShow) return [];
    return columns.filter((column) => column.hideInSearch !== true);
  }, [columns, searchShow]);

  /** 表单列 */
  const formColumn: FormColumn<T>[] = useMemo(() => {
    return columns.filter((column) => {
      if(formMode === 'update') {
        return column.hideInForm !== true && column.hideInUpdate !== true;
      } else {
        return column.hideInForm !== true && column.hideInCreate !== true;
      }
    });
  }, [columns, formMode]);

  /** 默认表格列 */
  const defaultTableColumns = useMemo(() => {
    return columns
      .filter((column) => column.hideInTable !== true && column.dataIndex)
      .map(column => omit(column, ['hideInTable', 'hideInForm', 'hideInSearch', 'search']));
  }, [columns]);

  /** 初始化列设置树数据 */
  useEffect(() => {
    const dataIndexList = defaultTableColumns.map(item => item.dataIndex!);
    setColumnsChecked(dataIndexList);
    setColumnSorted(dataIndexList);
  }, [defaultTableColumns]);

  /** 表格操作列 */
  const operate = useMemo((): TableColumnType<T>[] => {
    if (!operateShow) return [];
    return [
      {
        title: t('xinTableV2.operate'),
        key: 'operate',
        align: 'center',
        ...operateProps,
        render: (_, record) => (
          <Space>
            {beforeOperateRender?.(record)}
            {(typeof editShow === 'function' ? editShow(record) : editShow) && (
              <AuthButton auth={props.accessName + '.update'} key={'update'}>
                <Tooltip title={t('xinTableV2.edit')}>
                  <Button
                    type="primary"
                    icon={<EditOutlined />}
                    size={'small'}
                    onClick={() => handleUpdate(record)}
                  />
                </Tooltip>
              </AuthButton>
            )}
            {(typeof deleteShow === 'function' ? deleteShow(record) : deleteShow) !== false && (
              <AuthButton auth={props.accessName + '.delete'} key={'delete'}>
                <Tooltip title={t('xinTableV2.delete')}>
                  <Button
                    danger
                    type="primary"
                    icon={<DeleteOutlined />}
                    size={'small'}
                    onClick={() => handleDelete(record)}
                  />
                </Tooltip>
              </AuthButton>
            )}
            {afterOperateRender?.(record)}
          </Space>
        ),
      },
    ];
  }, [columns, operateShow, editShow, deleteShow, t]);

  /** 最终表格列，计算排序以及显示状态 */
  const tableColumns: TableColumnType<T>[] = useMemo(() => {
    // 过滤出选中的列
    const filteredColumns = defaultTableColumns.filter(column => 
      columnsChecked.includes(column.dataIndex as any)
    );
    // 根据 columnSorted 的顺序排序
    const sortedColumns = filteredColumns.sort((a, b) => {
      const indexA = columnSorted.indexOf(a.dataIndex as any);
      const indexB = columnSorted.indexOf(b.dataIndex as any);
      // 如果不在排序列表中，放到最后
      if (indexA === -1) return 1;
      if (indexB === -1) return -1;
      return indexA - indexB;
    });
    return [...sortedColumns, ...operate];
  }, [defaultTableColumns, columnsChecked, columnSorted, operate]);

  /** 新增按钮点击 */
  const handleCreate = () => {
    setFormMode('create');
    setFormDefaultValues(undefined);
    formRef.current?.resetFields();
    formRef.current?.open();
  }

  /** 修改按钮点击 */
  const handleUpdate = (record: T) => {
    setFormMode('update');
    setFormDefaultValues(record);
    formRef.current?.setFieldsValue(record);
    formRef.current?.open();
  }

  /** 删除记录 */
  const handleDelete = async (record: T) => {
    window.$modal?.confirm({
      title: t('xinTableV2.deleteConfirm', { id: record[rowKey] }),
      okText: t('xinTableV2.deleteOk'),
      cancelText: t('xinTableV2.deleteCancel'),
      onOk: async () => {
        await Delete(api + `/${record[rowKey]}`);
        window.$message?.success(t('xinTableV2.deleteSuccess'));
        await handleRequest(requestParams);
      }
    })
  };

  /** 表单提交处理 */
  const handleFinish = async (values: T) => {
    try {
      formRef.current?.setLoading(true);
      // 如果有自定义 onFinish，优先调用
      if (customHandleFinish) {
          const result = await customHandleFinish(values, formMode, formRef, formDefaultValues);
        if (result) {
          await handleRequest(requestParams);
          formRef.current?.close();
        }
        return;
      }
      if (formMode === 'create') {
        await Create(api, values);
        window.$message?.success(t('xinTableV2.form.createSuccess'));
      } else {
        if (formDefaultValues && rowKey) {
          await Update(api + `/${formDefaultValues[rowKey]}`, values);
          window.$message?.success(t('xinTableV2.form.updateSuccess'));
        } else {
          window.$message?.error(t('xinTableV2.form.updateKeyUndefined'));
          return false;
        }
      }
      await handleRequest(requestParams);
      formRef.current?.close();
    } finally {
      formRef.current?.setLoading(false);
    }
  };

  /** 密度设置菜单 */
  const densityMenu = {
    items: [
      {
        key: 'large',
        label: t('xinTableV2.density.default'),
        onClick: () => setDensity('large'),
      },
      {
        key: 'middle',
        label: t('xinTableV2.density.middle'),
        onClick: () => setDensity('middle'),
      },
      {
        key: 'small',
        label: t('xinTableV2.density.compact'),
        onClick: () => setDensity('small'),
      },
    ],
    selectedKeys: [density || 'large'],
  };

  // 处理拖拽排序
  const handleDrop: TreeProps['onDrop'] = useCallback((info: any) => {
    const { dragNode, node, dropPosition } = info;
    const dragKey = dragNode.key;
    const dropKey = node.key;

    setColumnSorted((prevSorted) => {
      const newSorted = [...prevSorted];
      const dragIndex = newSorted.indexOf(dragKey);
      const dropIndex = newSorted.indexOf(dropKey);

      if (dragIndex === -1 || dropIndex === -1) return prevSorted;

      // 移除拖拽的元素
      newSorted.splice(dragIndex, 1);

      // 计算插入位置
      let insertIndex = newSorted.indexOf(dropKey);
      if (dropPosition === -1) {
        // 放在目标节点前面
        insertIndex = insertIndex - 1;
      } else {
        // 放在目标节点后面
        insertIndex = insertIndex + 1;
      }

      // 插入到新位置
      newSorted.splice(insertIndex, 0, dragKey);

      return newSorted;
    });
  }, []);

  /** 列设置树数据  */
  const columnTreeData: DataNode[] = useMemo(() => {
    const filteredColumns = columns.filter(item => item.hideInTable !== true && item.dataIndex);
    // 根据 columnSorted 排序
    return filteredColumns
      .sort((a, b) => {
        const indexA = columnSorted.indexOf(a.dataIndex as any);
        const indexB = columnSorted.indexOf(b.dataIndex as any);
        if (indexA === -1) return 1;
        if (indexB === -1) return -1;
        return indexA - indexB;
      })
      .map((item) => ({
        key: item.dataIndex!,
        title: item.title,
      }));
  }, [columns, columnSorted]);

  /** 列设置选中改变 */
  const columnSettingCheck: TreeProps['onCheck'] = (keys) => {
    if(isArray( keys)) {
      setColumnsChecked(keys);
    }
  }

  /** 分页配置 */
  const paginationProps: TableProps['pagination'] = {
    showQuickJumper: true,
    showSizeChanger: true,
    showTotal: (total) => t('xinTableV2.total', { total }),
    pageSize: requestParams.pageSize,
    ...customPagination,
    current: requestParams.page,
    total,
  }

  return (
    <div>
      {/* 搜索表单 */}
      { searchShow && (
        <Card style={{marginBottom: 20}} {...cardProps}>
          <SearchForm<T>
            form={searchRef}
            columns={searchColumn}
            handleSearch={handleSearch}
            {...searchProps}
          />
        </Card>
      )}
      <Card {...cardProps}>
        <div style={{ marginBottom: 20 }}>
          {/* 操作栏 */}
          <Flex justify={'space-between'}>
            <Flex align={'center'}>
              {titleRender || <Typography.Title level={5}>{t('xinTableV2.queryTable')}</Typography.Title>}
            </Flex>
            <Space>
              {/* 快速搜索 */}
              {keywordSearchShow && (
                <Input.Search
                  onChange={keywordSearchChange}
                  placeholder={t('xinTableV2.keywordPlaceholder')}
                  style={{ width: 200 }}
                  value={requestParams.keywordSearch}
                  onSearch={handleKeywordSearch}
                />
              )}
              {toolBarRender.length > 0 && toolBarRender.map((item) => item)}
              {addShow && (
                <AuthButton auth={accessName + '.create'} key={'create'}>
                  <Button type="primary" onClick={handleCreate}>{t('xinTableV2.add')}</Button>
                </AuthButton>
              )}
              <Space size={1}>
                {/* 刷新表格 */}
                <Tooltip title={t('xinTableV2.refresh')}>
                  <Button
                    type="text"
                    size={'small'}
                    icon={<ReloadOutlined />}
                    onClick={() => handleRequest()}
                  />
                </Tooltip>
                {/* 密度设置 */}
                <Dropdown menu={densityMenu} trigger={['click']}>
                  <Button type="text" size={'small'} icon={<ColumnHeightOutlined />} />
                </Dropdown>
                {/* 边框设置 */}
                <Tooltip title={bordered ? t('xinTableV2.hideBorder') : t('xinTableV2.showBorder')}>
                  <Button
                    type="text"
                    size={'small'}
                    icon={bordered ? <BorderOutlined /> : <BorderlessTableOutlined />}
                    onClick={() => setBordered(!bordered)}
                  />
                </Tooltip>
                {/* 列设置 */}
                <Popover
                  content={(
                    <Tree
                      draggable
                      blockNode
                      checkable={true}
                      treeData={columnTreeData}
                      selectable={false}
                      onDrop={handleDrop}
                      checkedKeys={columnsChecked}
                      onCheck={columnSettingCheck}
                    />
                  )}
                  trigger="click"
                  placement="bottomRight"
                  title={t('xinTableV2.columnSettings')}
                >
                  <Tooltip title={t('xinTableV2.columnSettings')}>
                    <Button type="text" size={'small'} icon={<SettingOutlined />} />
                  </Tooltip>
                </Popover>
              </Space>
            </Space>
          </Flex>
        </div>

        <Table
          loading={loading}
          dataSource={dataSource}
          size={density}
          bordered={bordered}
          {...props}
          columns={tableColumns}
          rowKey={rowKey}
          onChange={handleTableChange}
          pagination={paginationShow ? paginationProps : false}
        />
      </Card>

      {/* 新增编辑表单 */}
      <XinForm
        {...formProps}
        columns={formColumn}
        formRef={formRef}
        layoutType="ModalForm"
        modalProps={{
          title: formMode === 'update' ? t('xinTableV2.form.editTitle') : t('xinTableV2.form.createTitle'),
          styles: { header: { marginBottom: 16 } },
          ...modalProps,
        }}
        onFinish={handleFinish}
      />
    </div>
  );
}