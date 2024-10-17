import { UploadOutlined } from '@ant-design/icons';
import type { MenuProps } from 'antd';
import { Button, Dropdown, message, Upload } from 'antd';
import React from 'react';
import IconFont from '@/components/IconFont';
import type { UploadProps } from 'antd';
export default (props: {selectGroup: GroupDataType,getFileList: (group_id: number)  => Promise<any>}) => {
  const {selectGroup,getFileList} = props

  const onChange: UploadProps['onChange'] = async (info) => {
    if (info.file.status !== 'uploading') {
      console.log(info.file, info.fileList);
    }
    if (info.file.status === 'done') {
      if(info.file.response.success) {
        message.success(`${info.file.name} 文件上传成功`);
        await getFileList(selectGroup.group_id)
      }else {
        message.error(info.file.response.msg);
      }
    } else if (info.file.status === 'error') {
      message.error(`${info.file.name} 文件上传失败，${info.file.response.msg}`);
    }
  }

  const UploadImage = (action: string,icon: string,title: string,accept: string) => (
    <>
      <Upload
        name={'file'}
        action={action}
        headers={{'X-Token': localStorage.getItem('x-token')!,}}
        onChange={onChange}
        multiple
        accept={accept}
        itemRender={() => <></>}
      >
        <Button
          type="link"
          block
          icon={
            <IconFont type={icon} className={icon}></IconFont>}
        >{title}</Button>
      </Upload>
    </>
  )


  const items: MenuProps['items'] = [
    {
      label: UploadImage(
        '/admin/file.upload/image?group_id=' + selectGroup.group_id,
        'icon-wenjianleixing-suolvetu-tupianwenjian',
        '上传图片文件',
        '.jpg,.jpeg,.png,.bmp,.gif,.avif,.webp'
      ),
      key: '0',
    },
    {
      label: UploadImage(
        '/admin/file.upload/zip?group_id=' + selectGroup.group_id,
        'icon-wenjianleixing-suolvetu-yasuowenjian',
        '上传压缩文件',
        '.zip,.rar'
      ),
      key: '2',
    },
    {
      label: UploadImage(
        '/admin/file.upload/mp3?group_id=' + selectGroup.group_id,
        'icon-wenjianleixing-suolvetu-shengyinwenjian',
        '上传音频文件',
        '.mp3,.wma,.wav,.ape,.flac,.ogg,.aac'
      ),
      key: '3',
    },
    {
      label: UploadImage(
        '/admin/file.upload/video?group_id=' + selectGroup.group_id,
        'icon-wenjianleixing-suolvetu-shipinwenjian',
        '上传视频文件',
        '.mp4,.mov,.wmv,.flv,.avl,.webm,.mkv'
      ),
      key: '4',
    },
    {
      label: UploadImage(
        '/admin/file.upload/annex?group_id=' + selectGroup.group_id,
        'icon-wenjianleixing-suolvetu-weizhiwenjian',
        '上传其它文件',
        '*'
      ),
      key: '5',
    },
  ];
  return (
    <>
      <Dropdown menu={{ items }} trigger={['click']}>
        <Button shape='round' icon={<UploadOutlined />} type={'primary'}>上传文件</Button>
      </Dropdown>
    </>
  );
}
