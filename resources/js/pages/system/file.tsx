import React, {useEffect, useMemo, useRef, useState} from 'react';
import {
  Button,
  Card,
  Col,
  Divider,
  Drawer,
  Dropdown,
  Form,
  Image,
  Input,
  message,
  Modal,
  Typography,
  Progress,
  Row,
  Select,
  Space,
  Spin,
  Table,
  Tag,
  Tooltip,
  Tree,
  TreeSelect,
  Upload,
  type UploadFile,
  type ButtonProps, Descriptions, type TagProps
} from 'antd';
import {useTranslation} from 'react-i18next';
import type {TreeProps} from 'antd/es/tree';
import {
  AudioOutlined,
  CloudUploadOutlined,
  CopyOutlined,
  DeleteOutlined,
  DownloadOutlined,
  EditOutlined,
  FileAddOutlined,
  FileImageOutlined,
  FileTextOutlined,
  FileZipOutlined,
  FolderOpenOutlined,
  MoreOutlined,
  ReloadOutlined,
  ScissorOutlined,
  UndoOutlined,
  UploadOutlined,
  VideoCameraOutlined
} from '@ant-design/icons';
import type {ISysFileGroup} from '@/domain/iSysFileGroup';
import type {ISysFileInfo, SysFileType} from '@/domain/iSysFile';
import {
  createFileGroup,
  deleteFileGroup,
  queryFileGroupList,
  updateFileGroup
} from '@/api/sys/sysFileGroup';
import {
  deleteFile,
  getFileList,
  uploadFile,
  forceDeleteFile,
  batchDeleteFiles,
  batchForceDeleteFiles,
  restoreFile,
  copyFile,
  moveFile,
  renameFile,
  cleanTrashed,
  getTrashedFileList, batchRestoreFiles
} from '@/api/sys/sysFile';
import XinForm from '@/components/XinForm';
import type { XinFormRef } from '@/components/XinForm/typings';
import type { FormColumn } from '@/components/XinFormField/FieldRender/typings';
import type {ColumnsType} from 'antd/es/table';
import dayjs from 'dayjs';
import IconFont from '@/components/IconFont';
import {isArray, isFunction} from "lodash";

interface FileOptions {
  value: SysFileType;
  color: TagProps['color'];
  label: React.ReactNode,
  icon: React.ReactNode
}

const FileManagement: React.FC = () => {
  const {t} = useTranslation();

  // 文件组相关状态
  const [fileGroups, setFileGroups] = useState<ISysFileGroup[]>([]);
  const [fileGroupMap, setFileGroupMap] = useState<Map<React.Key, ISysFileGroup>>(new Map());
  const [selectedGroupId, setSelectedGroupId] = useState<React.Key>(0);
  const [updateGroupData, setUpdateGroupData] = useState<ISysFileGroup | null>(null);
  const groupFormRef = useRef<XinFormRef | undefined>(undefined);
  const [groupSearchKeyword, setGroupSearchKeyword] = useState<string>();
  const [fileGroupLoading, setFileGroupLoading] = useState<boolean>(true);

  // 文件相关状态
  const [files, setFiles] = useState<ISysFileInfo[]>([]);
  const [fileList, setFileList] = useState<UploadFile[]>([]);
  const [loading, setLoading] = useState(false);
  const [uploadModalOpen, setUploadModalOpen] = useState(false);
  const [uploadFileType, setUploadFileType] = useState<SysFileType>(10);
  const [uploading, setUploading] = useState(false);
  const [uploadProgress, setUploadProgress] = useState(0);
  const [pagination, setPagination] = useState({current: 1, pageSize: 10, total: 0});

  // 复制移动状态
  const [targetModalOpen, setTargetModelOpen] = useState<boolean>(false);
  const [targetType, setTargetType] = useState<'copy' | 'move'>();
  const [targetOptionValue, setTargetOptionValue] = useState<number | number[]>();
  const [targetGroupId, setTargetGroupId] = useState<number>(0);

  // 重命名状态
  const [renameModalOpen, setRenameModalOpen] = useState(false);
  const [currentRenameFile, setCurrentRenameFile] = useState<ISysFileInfo | null>(null);
  const [newFileName, setNewFileName] = useState('');

  // 批量选择
  const [selectedRowKeys, setSelectedRowKeys] = useState<React.Key[]>([]);

  // 回收站
  const [trashFiles, setTrashFiles] = useState<ISysFileInfo[]>([]);
  const [trashLoading, setTrashLoading] = useState(false);
  const [trashModelOpen, setTrashModelOpen] = useState(false);
  const [trashPagination, setTrashPagination] = useState({current: 1, pageSize: 10, total: 0});

  // 文件详情
  const [detailDrawerOpen, setDetailDrawerOpen] = useState(false);
  const [currentDetailFile, setCurrentDetailFile] = useState<ISysFileInfo | null>(null);

  /** 加载文件夹列表 */
  const loadFileGroups = async () => {
    try {
      setFileGroupLoading(true);
      const {data} = await queryFileGroupList(groupSearchKeyword ? { keywordSearch: groupSearchKeyword } : undefined);
      const groups = data.data || [];
      setFileGroups(groups);
      const groupMap = new Map<React.Key, ISysFileGroup>();
      const buildMap = (items: ISysFileGroup[]) => {
        items.forEach(item => {
          groupMap.set(item.id, item);
          if (item.children) buildMap(item.children);
        });
      };
      buildMap(groups);
      setFileGroupMap(groupMap);
    } finally {
      setFileGroupLoading(false);
    }
  };

  /** 加载文件列表 */
  const loadFiles = async (page = 1, pageSize = 20) => {
    try {
      setLoading(true);
      setSelectedRowKeys([]);
      const {data} = await getFileList({
        group_id: (Number(selectedGroupId) || 0),
        page,
        pageSize
      });
      setFiles(data.data?.data || []);
      setPagination({
        current: data.data?.current_page || page,
        pageSize: data.data?.per_page || pageSize,
        total: data.data?.total || 0
      });
    } finally {
      setLoading(false);
    }
  };

  /** 加载回收站文件列表 */
  const loadTrashFiles = async (page = 1, pageSize = 10) => {
    try {
      setTrashLoading(true);
      setSelectedRowKeys([]);
      const {data} = await getTrashedFileList({
        page,
        pageSize
      });
      setTrashFiles(data.data?.data || []);
      setTrashPagination({
        current: data.data?.current_page || page,
        pageSize: data.data?.per_page || pageSize,
        total: data.data?.total || 0
      });
    } finally {
      setTrashLoading(false);
    }
  };

  useEffect(() => { loadFileGroups(); }, [groupSearchKeyword]);
  useEffect(() => { loadFiles(); }, [selectedGroupId]);

  /** 树形数据根节点 */
  const buildTreeData = useMemo(() => {
    const convertToTreeData = (groups: ISysFileGroup[]): any[] => groups.map(g => ({
      key: g.id,
      title: g.name,
      icon: <FolderOpenOutlined/>,
      children: g.children ? convertToTreeData(g.children) : undefined
    }));
    if(groupSearchKeyword) {
      return convertToTreeData(fileGroups);
    }
    return [
      {
        key: 0,
        title: t('sysFile.root'),
        icon: <FolderOpenOutlined/>,
        children: convertToTreeData(fileGroups)
      }
    ]
  }, [fileGroups]);

  /** 树形选择 */
  const onTreeSelect: TreeProps['onSelect'] = (keys) => {
    if(keys.length > 0) {
      setSelectedGroupId(keys[0])
    }
  };

  /** 添加文件夹 */
  const handleAddGroup = (parentId: number) => {
    setUpdateGroupData(null);
    groupFormRef.current?.resetFields();
    groupFormRef.current?.setFieldsValue({parent_id: parentId});
    groupFormRef.current?.open();
  };

  /** 编辑文件夹 */
  const handleEditGroup = (group: ISysFileGroup) => {
    setUpdateGroupData(group);
    groupFormRef.current?.setFieldsValue(group);
    groupFormRef.current?.open();
  };

  /** 保存文件夹 */
  const handleSaveGroup = async (values: ISysFileGroup) => {
    const isEdit = !!updateGroupData;
    await (isEdit ? updateFileGroup({...values, id: updateGroupData.id}) : createFileGroup(values));
    message.success(isEdit ? t('sysFile.saveFolderSuccess', {action: t('sysFile.actionEdit')}) : t('sysFile.saveFolderSuccess', {action: t('sysFile.actionAdd')}));
    groupFormRef.current?.close();
    await loadFileGroups();
    return true;
  };

  /** 删除文件夹 */
  const handleDeleteGroup = async (id: number) => {
    window.$modal?.confirm({
      title: t('sysFile.confirmDeleteFolder'),
      content: t('sysFile.deleteFolderHint'),
      okText: t('sysFile.ok'),
      cancelText: t('sysFile.cancel'),
      onOk: async () => {
        await deleteFileGroup(id);
        message.success(t('sysFile.deleteFolderSuccess'));
        if (selectedGroupId === id) setSelectedGroupId(0);
        await loadFileGroups();
      }
    });
  };

  /** 上传文件 */
  const handleUpload = async () => {
    if (!fileList.length) return message.warning(t('sysFile.selectFileWarning'));
    try {
      setUploading(true);
      for (const file of fileList) {
        await uploadFile(file.originFileObj as File, uploadFileType, Number(selectedGroupId) || undefined, setUploadProgress);
      }
      message.success(t('sysFile.uploadSuccess'));
      setUploadModalOpen(false);
      setFileList([]);
      setUploadProgress(0);
      await loadFiles();
    } finally {
      setUploading(false);
    }
  };

  /** 删除文件 */
  const handleDeleteFile = async (id: number) => {
    window.$modal?.confirm({
      title: t('sysFile.confirmDelete'),
      okText: t('sysFile.ok'),
      cancelText: t('sysFile.cancel'),
      onOk: async () => {
        await deleteFile(id);
        message.success(t('sysFile.deleteSuccess'));
        await loadFiles();
      }
    })
  };

  /** 批量删除 */
  const handleBatchDelete = async () => {
    if (!selectedRowKeys.length) return message.warning(t('sysFile.noSelected'));
    window.$modal?.confirm({
      title: t('sysFile.confirmBatchDelete', { count: selectedRowKeys.length }),
      okText: t('sysFile.ok'),
      cancelText: t('sysFile.cancel'),
      onOk: async () => {
        await batchDeleteFiles(selectedRowKeys as number[]);
        message.success(t('sysFile.batchDeleteSuccess'));
        await loadFiles();
      }
    });
  };

  /** 提交（移动、复制） */
  const handleTargetOk = async () => {
    if (!targetOptionValue) return message.warning(t('sysFile.noSelected'));
    const isBatch = isArray(targetOptionValue);
    if (isBatch && targetOptionValue.length < 1) return message.warning(t('sysFile.noSelected'));
    if(targetType === 'move') {
      await moveFile(targetOptionValue, targetGroupId);
      message.success(t('sysFile.moveSuccess'));
    } else {
      await copyFile(targetOptionValue, targetGroupId);
      message.success(t('sysFile.moveSuccess'));
    }
    setTargetModelOpen(false);
    await loadFiles();
  };

  /** 打开（移动、复制）弹窗 */
  const openTargetModal = (ids: number | number[], type: 'move' | 'copy') => {
    setTargetType(type);
    setTargetOptionValue(ids);
    setTargetModelOpen(true);
  };

  /** 批量（移动、复制）操作 */
  const batchOption: ButtonProps['onClick'] = (e) => {
    const type = e.currentTarget.dataset.type as ('copy' | 'move');
    console.log(type, e.currentTarget.dataset);
    const keys = selectedRowKeys.map(key => Number(key));
    openTargetModal(keys, type);
  }

  /** 打开重命名弹窗 */
  const openRenameModal = (file: ISysFileInfo) => {
    setCurrentRenameFile(file);
    setNewFileName(file.file_name || '');
    setRenameModalOpen(true);
  };

  /** 确认重命名 */
  const handleRename = async () => {
    if (!currentRenameFile?.id || !newFileName.trim()) return;
    await renameFile(currentRenameFile.id, newFileName.trim());
    message.success(t('sysFile.renameSuccess'));
    setRenameModalOpen(false);
    await loadFiles();
  };

  /** 打开回收站 */
  const openTrash = async () => {
    setTrashModelOpen(true);
    setSelectedRowKeys([]);
    await loadTrashFiles();
  }

  /** 关闭回收站 */
  const closeTrash = async () => {
    setTrashModelOpen(false);
    setSelectedRowKeys([]);
    await loadFiles();
  }

  /** 彻底删除文件 */
  const handleForceDeleteFile = async (id: number) => {
    window.$modal?.confirm({
      title: t('sysFile.confirmForceDelete'),
      okText: t('sysFile.ok'),
      cancelText: t('sysFile.cancel'),
      onOk: async () => {
        await forceDeleteFile(id);
        message.success(t('sysFile.forceDeleteSuccess'));
        await loadTrashFiles();
      }
    })
  };

  /** 恢复文件 */
  const handleRestoreFile = async (id: number) => {
    window.$modal?.confirm({
      title: t('sysFile.confirmRestore'),
      okText: t('sysFile.ok'),
      cancelText: t('sysFile.cancel'),
      onOk: async () => {
        await restoreFile(id);
        message.success(t('sysFile.restoreSuccess'));
        await loadTrashFiles();
      }
    })
  };

  /** 批量彻底删除 */
  const handleBatchForceDelete = async () => {
    if (!selectedRowKeys.length) return message.warning(t('sysFile.noSelected'));
    window.$modal?.confirm({
      title: t('sysFile.confirmBatchForceDelete', {count: selectedRowKeys.length}),
      okText: t('sysFile.ok'),
      cancelText: t('sysFile.cancel'),
      onOk: async () => {
        await batchForceDeleteFiles(selectedRowKeys as number[]);
        message.success(t('sysFile.batchForceDeleteSuccess'));
        await loadTrashFiles();
      }
    })
  };

  /** 批量恢复 */
  const handleBatchRestore = async () => {
    if (!selectedRowKeys.length) return message.warning(t('sysFile.noSelected'));
    window.$modal?.confirm({
      title: t('sysFile.confirmBatchRestore', {count: selectedRowKeys.length}),
      okText: t('sysFile.ok'),
      cancelText: t('sysFile.cancel'),
      onOk: async () => {
        await batchRestoreFiles(selectedRowKeys as number[]);
        message.success(t('sysFile.batchRestoreSuccess'));
        await loadTrashFiles();
      }
    })
  };

  /** 清空回收站 */
  const handleEmptyTrash = async () => {
    window.$modal?.confirm({
      title: t('sysFile.confirmEmptyTrash'),
      okText: t('sysFile.ok'),
      cancelText: t('sysFile.cancel'),
      onOk: async () => {
        const {data} = await cleanTrashed();
        message.success(t('sysFile.emptyTrashSuccess', {count: data.data?.count || 0}));
        await loadTrashFiles();
      }
    })
  };

  /** 打开文件详情 */
  const openFileDetail = (file: ISysFileInfo) => {
    setCurrentDetailFile(file);
    setDetailDrawerOpen(true);
  };

  /** 格式化文件大小 */
  const formatFileSize = (size = 0) => {
    const units = ['B', 'KB', 'MB', 'GB'];
    let i = 0, s = size;
    while (s >= 1024 && i < 3) { s /= 1024; i++; }
    return `${s.toFixed(2)} ${units[i]}`;
  };

  /** 文件类型 */
  const fileOptions: FileOptions[] = [
    {
      value: 10,
      color: 'purple',
      icon: <FileImageOutlined />,
      label: t('sysFile.type.image')
    },
    {
      value: 20,
      color: 'blue',
      icon: <AudioOutlined />,
      label: t('sysFile.type.audio')
    },
    {
      value: 30,
      color: 'magenta',
      icon: <VideoCameraOutlined />,
      label: t('sysFile.type.video')
    },
    {
      value: 40,
      color: 'orange',
      icon: <FileZipOutlined />,
      label: t('sysFile.type.archive')
    },
    {
      value: 50,
      color: 'yellow',
      icon: <FileTextOutlined />,
      label: t('sysFile.type.other')
    }
  ];

  /** 文件类型渲染 */
  const fileTypeRender = (type: SysFileType) => {
    const options = fileOptions.find(opt => opt.value === type);
    return <Tag color={options?.color}>{options?.label}</Tag>;
  }

  /** 下载文件 */
  const handleDownloadFile = async (id: number) => {
    const link = document.createElement('a');
    link.href = import.meta.env.VITE_BASE_URL + `/sys/file/list/download/${id}`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  /** 文件组表单列 */
  const groupFormColumns: FormColumn<ISysFileGroup>[] = [
    {
      title: t('sysFile.folderName'),
      dataIndex: 'name',
      valueType: 'text',
      rules: [{required: true, message: t('sysFile.folderNameRequired')}]
    },
    {
      title: t('sysFile.parentFolder'),
      dataIndex: 'parent_id',
      valueType: 'treeSelect',
      fieldProps: {
        treeData: buildTreeData,
        fieldNames: {label: 'title', value: 'key'},
        disabled: true
      }
    },
    {
      title: t('sysFile.sort'),
      dataIndex: 'sort',
      valueType: 'digit',
      rules: [{required: true, message: t('sysFile.sortRequired')}],
      initialValue: 0
    },
    {
      title: t('sysFile.describe'),
      dataIndex: 'describe',
      valueType: 'textarea'
    }
  ]

  /** 文件列 */
  const columns: ColumnsType<ISysFileInfo> = [
    {
      title: t('sysFile.fileName'),
      dataIndex: 'file_name',
      ellipsis: true,
      width: 320,
      render: (name: string, r: ISysFileInfo) => <a onClick={() => openFileDetail(r)}>{name}</a>
    },
    {
      title: t('sysFile.fileSize'),
      dataIndex: 'file_size',
      width: 80,
      align: 'center',
      render: formatFileSize,
      sorter: true
    },
    {
      title: t('sysFile.fileType'),
      dataIndex: 'file_type',
      width: 80,
      align: 'center',
      render: fileTypeRender
    },
    {
      title: t('sysFile.disk'),
      dataIndex: 'disk',
      width: 80,
      align: 'center',
      render: (disk: string) => <Tag>{disk}</Tag>
    },
    {
      title: t('sysFile.createdAt'),
      dataIndex: 'created_at',
      width: 180,
      align: 'center',
      render: (v) => v ? dayjs(v).format('YYYY-MM-DD HH:mm:ss') : '-'
    },
    {
      title: t('sysFile.preview'),
      dataIndex: 'preview_url',
      width: 80,
      align: 'center',
      render: (url: string) => (
        <div style={{display: 'flex', alignItems: 'center', justifyContent: 'center'}}>
          <Image
            preview={false}
            src={url}
            width={36}
            height={36}
          />
        </div>
      )
    },
    {
      title: t('sysFile.action'), width: 80, key: 'action', align: 'center', fixed: 'right',
      render: (_, file) => (
        <Dropdown
          trigger={['click']}
          menu={{
            items: [
              {
                key: 'download',
                icon: <DownloadOutlined/>,
                label: t('sysFile.download'),
                onClick: () => handleDownloadFile(file.id!)
              },
              {
                key: 'rename',
                icon: <EditOutlined/>,
                label: t('sysFile.rename'),
                onClick: () => openRenameModal(file)
              },
              {
                key: 'move',
                icon: <ScissorOutlined/>,
                label: t('sysFile.move'),
                onClick: () => openTargetModal(file.id!, 'move')
              },
              {
                key: 'copy',
                icon: <CopyOutlined/>,
                label: t('sysFile.copy'),
                onClick: () => openTargetModal(file.id!, 'copy')
              },
              {
                type: 'divider'
              },
              {
                key: 'delete',
                label: <span style={{color: '#ff4d4f'}}>{t('sysFile.delete')}</span>,
                icon: <DeleteOutlined style={{color: '#ff4d4f'}}/>,
                onClick: () => handleDeleteFile(file.id!)
              }
            ]
          }}
        >
          <Button icon={<MoreOutlined/>} size="small"/>
        </Dropdown>
      )
    }
  ];

  /** 回收站文件列 */
  const trashColumns: ColumnsType<ISysFileInfo> = [
    {
      title: t('sysFile.fileName'),
      dataIndex: 'file_name',
      ellipsis: true,
      width: 180,
    },
    {
      title: t('sysFile.fileSize'),
      dataIndex: 'file_size',
      width: 80,
      align: 'center',
      render: formatFileSize,
      sorter: true
    },
    {
      title: t('sysFile.fileType'),
      dataIndex: 'file_type',
      width: 80,
      align: 'center',
      render: fileTypeRender
    },
    {
      title: t('sysFile.disk'),
      dataIndex: 'disk',
      width: 80,
      align: 'center',
      render: (disk: string) => <Tag>{disk}</Tag>
    },
    {
      title: t('sysFile.deletedAt'),
      dataIndex: 'deleted_at',
      width: 120,
      align: 'center',
      render: (v) => v ? dayjs(v).format('YYYY-MM-DD HH:mm:ss') : '-'
    },
    {
      title: t('sysFile.action'),
      width: 80,
      key: 'action',
      align: 'center',
      fixed: 'right',
      render: (_, file) => (
        <Space>
          <Tooltip title={t('sysFile.restore')}>
            <Button
              size="small"
              icon={<UndoOutlined/>}
              onClick={() => handleRestoreFile(file.id!)}
            />
          </Tooltip>
          <Tooltip title={t('sysFile.forceDelete')}>
            <Button
              danger
              size="small"
              type="primary"
              icon={<DeleteOutlined/>}
              onClick={() => handleForceDeleteFile(file.id!)}
            />
          </Tooltip>
        </Space>
      )
    }
  ];

  /** 文件组标题渲染 **/
  const treeTitleRender: TreeProps['titleRender'] = (node) => (
    <Dropdown trigger={['click']} menu={{
      items: [
        {
          key: 'add',
          label: (
            <Space>
              <FileAddOutlined/>
              {t('sysFile.addFolder')}
            </Space>
          ),
          onClick: () => handleAddGroup(Number(node.key))
        },
        {
          key: 'edit',
          label: (
            <Space>
              <EditOutlined/>
              {t('sysFile.editFolder')}
            </Space>
          ),
          disabled: Number(node.key) === 0,
          onClick: () => fileGroupMap.has(node.key) && handleEditGroup(fileGroupMap.get(node.key)!)},
        { type: 'divider' },
        {
          key: 'del',
          danger: true,
          icon: <DeleteOutlined/>,
          disabled: Number(node.key) === 0,
          onClick: () => handleDeleteGroup(Number(node.key)),
          label: t('sysFile.deleteFolder')
        }
      ]
    }}>
      <Space>
        <IconFont name="icon-wenjianjia" />
        { isFunction(node.title) ? node.title(node) : node.title }
      </Space>
    </Dropdown>
  )

  return (
    <Row gutter={[16, 16]}>
      {/* 左侧文件夹树 */}
      <Col xs={24} lg={4}>
        <Card 
          title={
            <Space>
              <IconFont style={{fontSize: 18}} name='icon-wenjianjia' />
              {t('sysFile.fileFolder')}
            </Space>
          }
          variant={'borderless'}
          styles={{
            header: { paddingInline: 16, paddingBlock: 0, minHeight: 48 },
            body: { padding: 16, minHeight: 52 },
          }}
        >
          <Input.Search
            placeholder={t('sysFile.folderSearchPlaceholder')}
            style={{marginBottom: 16}}
            onSearch={(value) => setGroupSearchKeyword(value)}
          />
          <Spin spinning={fileGroupLoading} tip={t('sysFile.loading')} size="small">
            <div style={{minHeight: 200}}>
              { fileGroups.length > 0 && (
                <Tree
                  showLine
                  defaultExpandAll
                  onSelect={onTreeSelect}
                  treeData={buildTreeData}
                  defaultExpandedKeys={[0]}
                  selectedKeys={[selectedGroupId]}
                  titleRender={treeTitleRender}
                />
              )}
            </div>
          </Spin>
        </Card>
      </Col>

      {/* 右侧文件列表 */}
      <Col xs={24} lg={20}>
        <Card variant={'borderless'} styles={{body: { paddingBlock: 16 }}}>
          <Space wrap style={{ marginBottom: 16 }}>
            {/* 上传按钮 */}
            <Button
              type="primary"
              icon={<UploadOutlined/>}
              children={t('sysFile.upload')}
              onClick={() => setUploadModalOpen(true)}
            />
            {/* 批量操作 */}
            {( selectedRowKeys.length > 0 && !trashModelOpen ) && (
              <>
                <Button
                  danger
                  icon={<DeleteOutlined/>}
                  onClick={handleBatchDelete}
                  children={t('sysFile.batchDelete')}
                />
                <Button
                  icon={<ScissorOutlined/>}
                  data-type={'move'}
                  onClick={batchOption}
                  children={t('sysFile.batchMove')}
                />
                <Button
                  icon={<CopyOutlined/>}
                  data-type={'copy'}
                  onClick={batchOption}
                  children={t('sysFile.batchCopy')}
                />
              </>
            )}
            {/* 刷新按钮 */}
            <Tooltip title={t('sysFile.trash')}>
              <Button icon={<DeleteOutlined/>} onClick={() => openTrash()} children={t('sysFile.trash')}/>
            </Tooltip>
            <Tooltip title={t('sysFile.refresh')}>
              <Button icon={<ReloadOutlined/>} onClick={() => loadFiles()}/>
            </Tooltip>
          </Space>

          {/* 文件表格 */}
          <Table
            columns={columns}
            dataSource={files}
            rowKey="id"
            size={'small'}
            loading={loading}
            pagination={{
              ...pagination,
              onChange: loadFiles,
              showSizeChanger: true,
              showTotal: (total) => t('sysFile.totalFiles', { total })
            }}
            rowSelection={{
              selectedRowKeys,
              onChange: setSelectedRowKeys,
            }}
            scroll={{x: 1000}}
          />
        </Card>
      </Col>

      {/* 文件分组表单 */}
      <XinForm<ISysFileGroup>
        formRef={groupFormRef}
        layoutType="ModalForm"
        columns={groupFormColumns}
        onFinish={handleSaveGroup}
        modalProps={{
          title: updateGroupData ? t('sysFile.editFolderTitle') : t('sysFile.addFolderTitle'),
          onCancel: () => groupFormRef.current?.close()
        }}
        trigger={<span style={{display: 'none'}} />}
      />

      {/* 回收站抽屉 */}
      <Drawer
        title={t('sysFile.trash')}
        open={trashModelOpen}
        width={860}
        onClose={closeTrash}
      >
        <Space style={{ marginBottom: 20 }}>
          <Button
            disabled={selectedRowKeys.length === 0}
            type={'primary'}
            icon={<UndoOutlined/>}
            onClick={handleBatchRestore}
            children={t('sysFile.batchRestore')}
          />
          <Button
            disabled={selectedRowKeys.length === 0}
            icon={<DeleteOutlined/>}
            onClick={handleBatchForceDelete}
            children={t('sysFile.batchForceDelete')}
          />
          <Button
            danger
            type={'primary'}
            icon={<DeleteOutlined/>}
            onClick={handleEmptyTrash}
            children={t('sysFile.emptyTrash')}
          />
        </Space>
        <Table
          columns={trashColumns}
          dataSource={trashFiles}
          rowKey="id"
          size={'small'}
          loading={trashLoading}
          pagination={{
            ...trashPagination,
            onChange: loadTrashFiles,
            showSizeChanger: true,
            showTotal: (total) => t('sysFile.totalFiles', { total })
          }}
          rowSelection={{
            selectedRowKeys,
            onChange: setSelectedRowKeys,
          }}
          scroll={{x: 800}}
        />
      </Drawer>

      {/* 上传文件表单 */}
      <Modal
        title={t('sysFile.uploadTitle')}
        open={uploadModalOpen}
        onOk={handleUpload}
        onCancel={() => {
          setUploadModalOpen(false);
          setFileList([]);
          setUploadProgress(0);
        }}
        confirmLoading={uploading}
      >
        <Form layout="vertical">
          <Form.Item label={t('sysFile.uploadFileType')} required>
            <Select
              value={uploadFileType}
              onChange={(v) => setUploadFileType(v as SysFileType)}
              options={fileOptions}
            />
          </Form.Item>
          <Form.Item label={t('sysFile.selectFile')} required>
            <Upload.Dragger 
              fileList={fileList} 
              onChange={({fileList}) => setFileList(fileList)} 
              beforeUpload={() => false} 
              multiple
              style={{borderRadius: 8}}
            >
              <p className="ant-upload-drag-icon">
                <CloudUploadOutlined style={{fontSize: 48, color: '#1890ff'}} />
              </p>
              <p className="ant-upload-text">{t('sysFile.uploadDragText')}</p>
              <p className="ant-upload-hint">{t('sysFile.uploadHint')}</p>
            </Upload.Dragger>
          </Form.Item>
          {uploading && <Form.Item label={t('sysFile.uploadProgress')}><Progress percent={uploadProgress} status="active"/></Form.Item>}
        </Form>
      </Modal>

      {/* 重命名弹窗 */}
      <Modal
        title={t('sysFile.renameTitle')}
        open={renameModalOpen}
        onOk={handleRename}
        styles={{ body: { paddingBottom: 16 } }}
        onCancel={() => setRenameModalOpen(false)}
      >
        <div style={{ marginTop: 16, marginBottom: 16 }}>
          {t('sysFile.renameDescription')}
        </div>
        <Input
          value={newFileName}
          onChange={(e) => setNewFileName(e.target.value)}
          placeholder={t('sysFile.newFileNameRequired')}
        />
      </Modal>

      {/* 复制移动弹窗 */}
      <Modal
        title={ targetType === 'copy' ? t('sysFile.copyTitle') : t('sysFile.moveTitle') }
        open={targetModalOpen}
        onOk={handleTargetOk}
        styles={{ body: { paddingBottom: 16 } }}
        onCancel={() => setTargetModelOpen(false)}
      >
        <div style={{ marginTop: 16, marginBottom: 16 }}>
          { targetType === 'copy' ? t('sysFile.copyDescription') : t('sysFile.moveDescription') }
        </div>
        <TreeSelect
          value={targetGroupId}
          onChange={setTargetGroupId}
          treeData={buildTreeData}
          fieldNames={{label: 'title', value: 'key'}}
          style={{width: '100%'}}
        />
      </Modal>

      {/* 文件详情抽屉 */}
      <Drawer
        title={t('sysFile.fileDetail')}
        placement="right"
        width={480}
        open={detailDrawerOpen}
        onClose={() => setDetailDrawerOpen(false)}
      >
        {currentDetailFile && (
          <>
            {/* 文件预览 */}
            <div style={{display: 'flex', justifyContent: 'center', alignItems: 'center'}}>
              <Image
                src={currentDetailFile.preview_url}
                alt={currentDetailFile.file_name}
                preview={false}
              />
            </div>
            <Divider />

            {/* 基本信息 */}
            <Descriptions
              title={t('sysFile.basicInfo')}
              column={1}
              items={[
                {
                  key: 'name',
                  label: t('sysFile.fileName'),
                  children: currentDetailFile.file_name,
                },
                {
                  key: 'size',
                  label: t('sysFile.fileSize'),
                  children: formatFileSize(currentDetailFile.file_size),
                },
                {
                  key: 'type',
                  label: t('sysFile.fileType'),
                  children: fileTypeRender(currentDetailFile.file_type || 10),
                },
                {
                  key: 'ext',
                  label: t('sysFile.fileExt'),
                  children: currentDetailFile.file_ext,
                },
                {
                  key: 'disk',
                  label: t('sysFile.storageMethod'),
                  children: currentDetailFile.disk,
                },
                {
                  key: 'path',
                  label: t('sysFile.filePath'),
                  children: currentDetailFile.file_path,
                },
                {
                  key: 'group',
                  label: t('sysFile.fileGroup'),
                  children: fileGroupMap.get(currentDetailFile.group_id || 0)?.name || t('sysFile.ungrouped'),
                },
                {
                  key: 'created_at',
                  label: t('sysFile.createdAt'),
                  children: currentDetailFile.created_at,
                },
                {
                  key: 'updated_at',
                  label: t('sysFile.updatedAt'),
                  children: currentDetailFile.updated_at,
                },
                {
                  key: 'url',
                  label: t('sysFile.accessUrl'),
                  children: (
                    <Typography.Link copyable>
                      {currentDetailFile.file_url}
                    </Typography.Link>
                  )
                },
              ]}
            />
          </>
        )}
      </Drawer>
    </Row>
  );
};

export default FileManagement;