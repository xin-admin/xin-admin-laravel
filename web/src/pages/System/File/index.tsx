import { Button, Checkbox, Empty, Flex, Image, Menu, MenuProps, message, Pagination, Popconfirm, Space } from 'antd';
import { ProCard } from '@ant-design/pro-components';
import React, { useEffect, useState } from 'react';
import './index.less';
import IconFont from '@/components/IconFont';
import { useBoolean } from 'ahooks';
import { DeleteOutlined, FolderOutlined } from '@ant-design/icons';
import { deleteApi, listApi } from '@/services/common/table';
import { IFileGroup } from '@/domain/iFileGroup';
import { IFile } from '@/domain/iFile';

type MenuItem = Required<MenuProps>['items'][number];

export default () => {
  // 分组列表
  const [fileGroup, setFileGroup] = useState<IFileGroup[]>([]);
  // 分组菜单
  const [groupMenu, setGroupMenu] = useState<MenuItem[]>([]);
  // 选择分组
  const [selectGroup, setSelectGroup] = useState<string[]>(['14']);
  // 文件列表
  const [fileList, setFileList] = useState<IFile[]>([]);
  // 选择文件
  const [selectFile, setSelectFile] = useState<number[]>([]);
  // 分页
  const [pageData, setPageData] = useState({ page: 1, total: 0 });
  // 加载状态
  const [loading, setLoading] = useState<boolean>(false);
  // 选择状态
  const [selectShow, setSelectShow] = useBoolean();
  // 获取文件列表
  const getFileList = async (current = 1) => {
    if (!selectGroup) {
      message.warning('请选择文件分组！');
      return;
    }
    let data = {
      pageSize: 50,
      current,
      group_id: Number(selectGroup[0]),
    };
    let res = await listApi('/system/file', data);
    setFileList(res.data.data);
    setPageData({
      page: res.data.current_page,
      total: res.data.total,
    });
  };
  // 删除文件
  const deleteConfirm = async () => {
    if (!selectGroup) {
      message.warning('请选择分组！');
      return;
    }
    if (selectFile.length === 0) {
      message.warning('请选择文件！');
      return;
    }
    setLoading(true);
    await deleteApi('/system/file', { file_id: selectFile.join(',') });
    await getFileList(pageData.page);
    setSelectFile([]);
    message.success('删除成功！');
    setLoading(false);
  };
  // 选择分组
  useEffect(() => {
    setLoading(true);
    getFileList(1).finally(() => {
      setLoading(false);
    });
  }, [selectGroup]);
  // 获取菜单列表
  useEffect(() => {
    listApi('/system/file/group').then((res) => {
      let menu: MenuItem[] = res.data.data.map((item: IFileGroup) => {
        return {
          label: item.name,
          key: item.group_id,
          icon: <FolderOutlined />,
        };
      });
      setGroupMenu(menu);
    });
  }, []);

  const fileExtra = (
    <Space>
      {selectShow &&
        <Popconfirm
          title="Delete the task"
          description={`你确定要删除这 ${selectFile.length} 个文件吗？`}
          onConfirm={deleteConfirm}
        >
          <Button type={'primary'} icon={<DeleteOutlined />} shape="round" danger>
            批量删除
          </Button>
        </Popconfirm>
      }
      <Button
        onClick={() => setSelectShow.toggle()}
        type={'primary'}
        shape="round"
        icon={<IconFont name={'icon-piliangxuanze'}></IconFont>}
      >
        {selectShow ? '取消选择' : '批量选择'}
      </Button>
    </Space>
  );

  return (
    <ProCard ghost gutter={20} wrap={false}>
      <ProCard bordered headerBordered colSpan={'260px'} title={'文件分组'}>
        <Menu
          items={groupMenu}
          style={{ border: 'none' }}
          selectedKeys={selectGroup}
          onSelect={(info) => setSelectGroup(info.selectedKeys)}
        />
      </ProCard>
      <ProCard loading={loading} bordered headerBordered colSpan={'auto'} title={'文件列表'} style={{ width: '100%' }}
               extra={fileExtra}>
        <div style={{ minHeight: '500px' }}>
          {fileList.length > 0 ?
            <Flex wrap="wrap" gap="middle">
              {fileList.map((item) => (
                <div className={'image-card'} key={item.file_id}>
                  {selectShow &&
                    <div className={'card'} onClick={() => {
                      if (selectFile?.includes(item.file_id)) {
                        setSelectFile(selectFile.filter((key) => key !== item.file_id));
                      } else {
                        setSelectFile([...selectFile, item.file_id]);
                      }
                    }}>
                      <Checkbox checked={selectFile.includes(item.file_id)}></Checkbox>
                    </div>
                  }
                  <div className="wrapper">
                    <Image height={60} preview={item.file_type === 10 ? {} : false} className="file-icon"
                           src={item.preview_url} />
                    <p className="gi-line-1 file-name">{item.file_name}</p>
                  </div>
                </div>
              ))}
            </Flex> : <Empty />
          }
        </div>
        <Pagination
          style={{ textAlign: 'left' }}
          current={pageData.page}
          total={pageData.total}
          pageSize={50}
          onChange={getFileList}
        />
      </ProCard>
    </ProCard>
  );
}
