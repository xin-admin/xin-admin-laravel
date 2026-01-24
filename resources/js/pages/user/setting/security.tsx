import {Button, Form, Input, message, type FormProps} from 'antd';
import {useState} from "react";
import {type PasswordParams, updatePassword} from "@/api/sys/sysUser";
import {useTranslation} from "react-i18next";

const Security = () => {
  const [form] = Form.useForm();
  const [loading, setLoading] = useState<boolean>(false);
  const {t} = useTranslation();
  /** 修改密码提交事件 */
  const onFinish: FormProps['onFinish'] = async (values: PasswordParams) => {
    try {
      setLoading(true);
      await updatePassword(values);
      form.resetFields();
      message.success(t("userSetting.changePassword.success"));
    }finally {
      setLoading(false);
    }
  }

  return (
    <Form<PasswordParams> form={form} layout="vertical" onFinish={onFinish} className={'w-full px-6 py-4'}>
      <Form.Item
        label={t("userSetting.changePassword.oldPassword")}
        name="oldPassword"
        rules={[{ required: true, message: t("userSetting.changePassword.oldPassword.message") }]}
      >
        <Input.Password />
      </Form.Item>
      <Form.Item
        label={t("userSetting.changePassword.newPassword")}
        name="newPassword"
        rules={[
          { required: true, message: t("userSetting.changePassword.requiredMessage") },
          { min: 8, message: t("userSetting.changePassword.minMessage") }
        ]}
      >
        <Input.Password />
      </Form.Item>
      <Form.Item
        label={t("userSetting.changePassword.rePassword")}
        name="rePassword"
        dependencies={['newPassword']}
        rules={[
          { required: true, message: t("userSetting.changePassword.requiredMessage") },
          ({ getFieldValue }) => ({
            validator(_, value) {
              if (!value || getFieldValue('newPassword') === value) {
                return Promise.resolve();
              }
              return Promise.reject(new Error(t("userSetting.changePassword.rePassword.message")));
            },
          }),
        ]}
      >
        <Input.Password />
      </Form.Item>
      <Button type="primary" block htmlType="submit" size="large" loading={loading}>
        {t("userSetting.changePassword.submit")}
      </Button>
    </Form>
  );
};

export default  Security;