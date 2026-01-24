import {UploadOutlined} from '@ant-design/icons';
import {Avatar, Button, Form, Input, message, Radio, Upload, type UploadProps, type FormProps} from 'antd';
import useAuthStore from "@/stores/user";
import {type InfoParams, updateInfo} from "@/api/sys/sysUser";
import {useState} from "react";
import {useTranslation} from "react-i18next";

const Info = () => {
  const userInfo = useAuthStore(state => state.userinfo);
  const getInfo = useAuthStore(state => state.info);
  const [form] = Form.useForm();
  const [loading, setLoading] = useState<boolean>(false);
  const {t} = useTranslation();

  /** 上传头像 */
  const uploadChange: UploadProps['onChange'] = async (info) => {
    console.log('Upload Change', info);
    if (info.file.status === 'done') {
      message.success('头像上传成功');
      await getInfo();
    }
  }

  /** 提交表单 */
  const onFinish: FormProps['onFinish'] = async (values: InfoParams) => {
    try {
      console.log('Received values:', values);
      setLoading(true);
      await updateInfo(values);
      await getInfo();
      message.success('更新用户信息成功！');
    } finally {
      setLoading(false);
    }
  }

  return (
      <div className="w-full px-6 py-4">
        <div className={'flex flex-col items-center mb-3'}>
          <Avatar size={120} src={userInfo?.avatar_url} className="mb-4 border-2 border-gray-200" />
          <Upload
            name="file"
            action={`${import.meta.env.VITE_BASE_URL}/admin/avatar`}
            headers={{
              // 直接从 localStorage 获取 token
              Authorization: `Bearer ${localStorage.getItem('token')}`
            }}
            showUploadList={false}
            onChange={uploadChange}
          >
            <Button icon={ <UploadOutlined /> }>{t("userSetting.baseInfo.updateAvatar")}</Button>
          </Upload>
          <p className="text-gray-500 text-sm mt-2">{t("userSetting.baseInfo.updateAvatarDesc")}</p>
        </div>
        <Form<InfoParams>
          form={form}
          layout="vertical"
          onFinish={onFinish}
          initialValues={{
            bio: userInfo?.bio,
            email: userInfo?.email,
            mobile: userInfo?.mobile,
            nickname: userInfo?.nickname,
            sex: userInfo?.sex,
            username: userInfo?.username,
          }}
        >
          <Form.Item
            label={t("userSetting.baseInfo.username")}
            name="username"
            rules={[{ required: true, message: t("userSetting.baseInfo.username.message") }]}
          >
            <Input className="w-full" disabled />
          </Form.Item>
          <Form.Item
            label={t("userSetting.baseInfo.nickname")}
            name="nickname"
            rules={[{ required: true, message: t("userSetting.baseInfo.nickname.message") }]}
          >
            <Input className="w-full" />
          </Form.Item>
          <Form.Item label={t("userSetting.baseInfo.sex")} name="sex">
            <Radio.Group options={[{ value: 0, label: t("userSetting.baseInfo.sex.0") }, { value: 1, label: t("userSetting.baseInfo.sex.1") }]}/>
          </Form.Item>
          <Form.Item label={t("userSetting.baseInfo.bio")} name="bio">
            <Input.TextArea rows={4} />
          </Form.Item>
          <Form.Item
            label={t("userSetting.baseInfo.email")}
            name="email"
            rules={[
              { type: 'email', message: t("userSetting.baseInfo.email.typeMessage") },
              { required: true, message: t("userSetting.baseInfo.email.requiredMessage") }
            ]}
          >
            <Input className="w-full" />
          </Form.Item>
          <Form.Item
            label={t("userSetting.baseInfo.mobile")}
            name="mobile"
            rules={[
              { required: true, message: t("userSetting.baseInfo.mobile.message") }
            ]}
          >
            <Input className="w-full" />
          </Form.Item>

          <Form.Item>
            <Button type="primary" htmlType="submit" size="large" loading={loading} block>
              {t("userSetting.baseInfo.submit")}
            </Button>
          </Form.Item>
        </Form>
      </div>
  );
};

export default Info;