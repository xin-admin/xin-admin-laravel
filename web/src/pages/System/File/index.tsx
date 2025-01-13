import { Button, Checkbox, Empty, Flex, Image, message, Pagination, Popconfirm, Space } from 'antd';
import { ProCard } from '@ant-design/pro-components';
import React, { useEffect, useState } from 'react';
import { DeleteFile } from '@/services/file/file';
import './index.less';
import UploadFile from './components/UploadFile';
import IconFont from '@/components/IconFont';
import { useBoolean } from 'ahooks';
import { DeleteOutlined } from '@ant-design/icons';
import { listApi } from '@/services/common/table';

export default () => {

  const [selectGroup, setSelectGroup] = useState<GroupDataType>({
    name: 'root',
    group_id: 0,
    sort: 0,
  });
  const [fileList, setFileList] = useState<FileType[]>([]);
  const [pageData, setPageData] = useState({
    page: 1,
    total: 0,
  });
  const [selectFile, setSelectFile] = useState<any[]>([]);
  const [selectShow, setSelectShow] = useBoolean();
  const getFileList = async (group_id = 0, current = 1) => {
    let res = await listApi('/system/file', { pageSize: 50, current, group_id });
    setFileList(res.data.data);
    setPageData({
      page: res.data.current_page,
      total: res.data.total,
    });
  };

  const deleteConfirm = async () => {
    if (selectFile.length === 0) {
      message.warning('请选择文件！');
      return;
    }
    await DeleteFile({ ids: selectFile.join(',') });
    await getFileList(selectGroup.group_id, pageData.page);
    setSelectFile([]);
    message.success('删除成功！');
  };

  useEffect(() => {
    getFileList(selectGroup.group_id, 1).then(() => {
    });
  }, [selectGroup]);

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
      <UploadFile getFileList={getFileList} selectGroup={selectGroup}></UploadFile>
    </Space>
  );

  const FileInfo = (props: { item: FileType }) => (
    <div className={'image-card'} key={props.item.file_id}>
      {selectShow &&
        <div className={'card'} onClick={() => {
          if (selectFile?.includes(props.item.file_id)) {
            setSelectFile(selectFile.filter((key) => key !== props.item.file_id));
          } else {
            setSelectFile([...selectFile, props.item.file_id]);
          }
        }}>
          <Checkbox checked={selectFile.includes(props.item.file_id)}></Checkbox>
        </div>
      }
      <div className="wrapper">
        <Image height={60} preview={props.item.file_type === 10 ? {} : false} className="file-icon"
               src={props.item.preview_url} />
        <p className="gi-line-1 file-name">{props.item.file_name}</p>
      </div>
    </div>
  );

  return (
    <ProCard ghost gutter={20} wrap={false}>
      <ProCard ghost colSpan={'260px'}>

      </ProCard>
      <ProCard bordered headerBordered colSpan={'auto'} title={'文件列表'} style={{ width: '100%' }} extra={fileExtra}>
        <div style={{ minHeight: '500px' }}>
          {fileList.length > 0 ?
            <Flex wrap="wrap" gap="middle">
              {fileList.map((item) => <FileInfo item={item} />)}
            </Flex>
            :
            <Empty />
          }
        </div>
        <Pagination
          style={{ textAlign: 'right' }}
          current={pageData.page}
          total={pageData.total}
          pageSize={50}
          onChange={async (page) => {
            await getFileList(selectGroup.group_id, page);
          }}
        />
      </ProCard>
    </ProCard>
  );
}
