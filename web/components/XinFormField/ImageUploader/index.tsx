import React, { useState, useMemo, useCallback, useEffect } from 'react';
import { Upload, message } from 'antd';
import { PlusOutlined } from '@ant-design/icons';
import ImgCrop from 'antd-img-crop';
import type { UploadFile, UploadProps } from 'antd';
import type { RcFile } from 'antd/es/upload';
import type { ImageUploaderProps } from './typings';
import type { ISysFileInfo } from '@/domain/iSysFile';
import { useTranslation } from 'react-i18next';

/**
 * 图片上传组件
 */
const ImageUploader: React.FC<ImageUploaderProps> = ({
  action,
  value,
  onChange,
  mode = 'single',
  disabled = false,
  maxCount,
  maxWidth = 1920,
  maxHeight = 1080,
  maxSize = 5,
  croppable = false,
  cropperOptions,
}) => {
  const { t } = useTranslation();
  const [fileList, setFileList] = useState<UploadFile[]>([]);

  // 计算最大上传数量
  const uploadMaxCount = useMemo(() => maxCount || (mode === 'single' ? 1 : 9), [mode, maxCount]);

  // 格式化图片列表
  const fileToList = (file: ISysFileInfo): UploadFile => ({
    uid: file.id?.toString() || `${file.id}`,
    name: file.file_name || `image-${file.id}`,
    status: 'done',
    url: file.preview_url,
    response: {
      success: true,
      data: file,
      message: 'Upload success',
    },
  });

  // 默认文件列表
  useEffect(() => {
    if (!value) {
      setFileList([]);
      return;
    }
    const valueArray = Array.isArray(value) ? value : [value];
    const newFileList: UploadFile[] = valueArray.map(fileToList);
    setFileList(newFileList);
  }, [value]);

  // 上传前校验
  const beforeUpload = useCallback((file: RcFile): Promise<boolean> => {
    return new Promise((resolve, reject) => {
      // 1. 文件类型校验
      const isImage = file.type.startsWith('image/');
      if (!isImage) {
        message.error(t('xinForm.imageUploader.error.notImage'));
        reject(false);
        return;
      }

      // 2. 文件大小校验
      const isLtMaxSize = file.size / 1024 / 1024 < maxSize;
      if (!isLtMaxSize) {
        message.error(t('xinForm.imageUploader.error.sizeExceeded', { maxSize }));
        reject(false);
        return;
      }

      // 3. 图片尺寸校验
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = (e) => {
        const img = document.createElement('img');
        img.src = e.target?.result as string;
        img.onload = () => {
          const isValidWidth = img.width <= maxWidth;
          const isValidHeight = img.height <= maxHeight;

          if (!isValidWidth || !isValidHeight) {
            message.error(t('xinForm.imageUploader.error.dimensionExceeded', { maxWidth, maxHeight }));
            reject(false);
            return;
          }

          resolve(true);
        };
        img.onerror = () => {
          message.error(t('xinForm.imageUploader.error.loadFailed'));
          reject(false);
        };
      };
      reader.onerror = () => {
        message.error(t('xinForm.imageUploader.error.readFailed'));
        reject(false);
      };
    });
  }, [maxSize, maxWidth, maxHeight]);

  // 处理文件列表变化
  const handleChange: UploadProps['onChange'] = ({ fileList: newFileList }) => {
    // 如果所有文件都被删除
    if (newFileList.length === 0) return;
    setFileList(newFileList);
    // 全部上传完成格式化图片列表
    if (newFileList.every((file) => file.status === 'done' || file.status === 'error')) {
      // 上传成功的文件
      const uploadedFiles = newFileList
        .filter((file) => file.status === 'done' && file.response)
        .map((file) => file.response.data as ISysFileInfo);
      onChange?.(uploadedFiles);
      // 上传失败的文件
      const errorFiles = newFileList.filter((file) => file.status === 'error');
      setFileList([...uploadedFiles.map(fileToList), ...errorFiles]);
    }
  };

  // 上传按钮
  const uploadButton = useMemo(() => (
    <button style={{ border: 0, background: 'none' }} type="button">
      <PlusOutlined />
      <div style={{ marginTop: 8 }}>{t('xinForm.imageUploader.uploadText')}</div>
    </button>
  ), [t]);

  // Upload 组件
  const uploadComponent = (
    <Upload
      listType="picture-card"
      name="file"
      fileList={fileList}
      beforeUpload={beforeUpload}
      onChange={handleChange}
      disabled={disabled}
      maxCount={uploadMaxCount}
      multiple={mode === 'multiple'}
      headers={{ 
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
      }}
      action={import.meta.env.VITE_BASE_URL + action}
      accept="image/*"
    >
      {fileList.length >= uploadMaxCount ? null : uploadButton}
    </Upload>
  );

  return (
    <>
      {croppable && mode === 'single' ? (
        <ImgCrop {...cropperOptions}>
          {uploadComponent}
        </ImgCrop>
      ) : (
        uploadComponent
      )}
    </>
  );
};

export default ImageUploader;
