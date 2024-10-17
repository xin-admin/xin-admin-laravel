import ImgCrop from 'antd-img-crop';
import React, { useEffect, useState } from 'react';
import { message, Upload } from 'antd';
import type { RcFile, UploadFile, UploadProps } from 'antd/es/upload/interface';
import { FormInstance } from 'antd/lib/form';

interface PropsType {
  dataIndex: string; // 值
  api: string; // 接口
  form: FormInstance; // 表单
  maxCount?: number; // 最大上传数量
  crop?: boolean; // 图片剪裁
  defaultFile?: string | [];
}


const UploadImgItem: React.FC<PropsType> = (props) => {
  const { form, dataIndex, api, maxCount = 1, crop, defaultFile } = props

  const [fileList, setFileList] = useState<UploadFile[]>();

  useEffect(() => {
    // 设置默认显示图片
    if (!defaultFile) {
      return
    }
    if (defaultFile instanceof Array) {
      setFileList(defaultFile.map((item, index): UploadFile => {
        return {
          uid: index.toString(),
          name: '',
          status: 'done',
          url: item,
        }
      }))
    } else {
      setFileList([{
        uid: '-1',
        name: '',
        status: 'done',
        url: defaultFile,
      }])
    }
  }, [defaultFile])

  const onChange: UploadProps['onChange'] = ({ fileList: newFileList }) => {
    setFileList(newFileList);
    if (newFileList.length === 0) {
      return
    }
    if (newFileList[0].status === 'done') {
      if(newFileList[0].response.success) {
        form.setFieldValue(dataIndex, newFileList[0].response.data.fileInfo.file_id)
      }else {
        message.warning(newFileList[0].response.msg)
        setFileList([])
      }
    }
  };

  const onPreview = async (file: UploadFile) => {
    let src = file.url as string;
    if (!src) {
      src = await new Promise((resolve) => {
        const reader = new FileReader();
        reader.readAsDataURL(file.originFileObj as RcFile);
        reader.onload = () => resolve(reader.result as string);
      });
    }
    const image = new Image();
    image.src = src;
    const imgWindow = window.open(src);
    imgWindow?.document.write(image.outerHTML);
  };

  return (
    <>
      {crop ?
        <ImgCrop rotationSlider>
          <Upload
            maxCount={maxCount}
            action={process.env.DOMAIN + api}
            listType="picture-card"
            fileList={fileList}
            onChange={onChange}
            onPreview={onPreview}
            headers={{
              'X-Token': localStorage.getItem('x-token')!,
              'X-User-Token': localStorage.getItem('x-user-token')!,
            }}
          >+ Upload</Upload>
        </ImgCrop>
        :
        <Upload
          maxCount={maxCount}
          action={process.env.DOMAIN + api}
          listType="picture-card"
          fileList={fileList}
          onChange={onChange}
          headers={{
            'X-Token': localStorage.getItem('x-token')!,
            'X-User-Token': localStorage.getItem('x-user-token')!,
          }}
        >+ Upload</Upload>
      }
    </>
  );
};

export default UploadImgItem;
