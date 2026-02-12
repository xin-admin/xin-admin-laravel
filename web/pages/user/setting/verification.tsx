import {SafetyCertificateOutlined, UploadOutlined} from "@ant-design/icons";
import {Button, Form, Input, message, Upload} from "antd";

const Verification = () => {
  return (
    <div className="px-6 py-4 max-w-2xl flex-auto">
      <div className="flex items-center mb-4">
        <SafetyCertificateOutlined className="text-2xl text-green-500 mr-3" />
        <div>
          <h3 className="font-bold text-lg">实名认证</h3>
          <p className="text-gray-500">完成实名认证可享受更多服务</p>
        </div>
      </div>

      <div className="bg-blue-50 p-4 rounded-md mb-6">
        <h4 className="font-medium text-blue-800 mb-2">认证状态: <span className="text-orange-500">未认证</span></h4>
        <p className="text-blue-700 text-sm">请上传您的身份证正反面照片进行实名认证</p>
      </div>

      <Form
        layout="vertical"
        onFinish={(values) => {
          console.log('Received values:', values);
          message.success('设置保存成功');
        }}
        className={'mb-5'}
      >
        <Form.Item
          label="真实姓名"
          name="realName"
          rules={[{ required: true, message: '请输入真实姓名!' }]}
        >
          <Input className="w-full" />
        </Form.Item>

        <Form.Item
          label="身份证号"
          name="idNumber"
          rules={[
            { required: true, message: '请输入身份证号!' },
            { pattern: /^\d{17}[\dXx]$/, message: '请输入有效的身份证号!' }
          ]}
        >
          <Input className="w-full" />
        </Form.Item>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <Form.Item
              label="身份证正面"
              name="idFront"
              rules={[{ required: true, message: '请上传身份证正面!' }]}
            >
              <Upload.Dragger
                name="idFront"
                action="https://www.mocky.io/v2/5cc8019d300000980a055e76"
                listType="picture"
                maxCount={1}
              >
                <p className="ant-upload-drag-icon">
                  <UploadOutlined />
                </p>
                <p className="ant-upload-text">点击或拖拽上传身份证正面</p>
                <p className="ant-upload-hint">支持 JPG/PNG 格式，大小不超过 5MB</p>
              </Upload.Dragger>
            </Form.Item>
          </div>

          <div>
            <Form.Item
              label="身份证反面"
              name="idBack"
              rules={[{ required: true, message: '请上传身份证反面!' }]}
            >
              <Upload.Dragger
                name="idBack"
                action="https://www.mocky.io/v2/5cc8019d300000980a055e76"
                listType="picture"
                maxCount={1}
              >
                <p className="ant-upload-drag-icon">
                  <UploadOutlined />
                </p>
                <p className="ant-upload-text">点击或拖拽上传身份证反面</p>
                <p className="ant-upload-hint">支持 JPG/PNG 格式，大小不超过 5MB</p>
              </Upload.Dragger>
            </Form.Item>
          </div>
        </div>

        <Button type="primary" htmlType="submit" block size="large">
          提交认证
        </Button>
      </Form>

      <div className="bg-gray-50 p-4 rounded-md text-sm text-gray-600">
        <h4 className="font-medium mb-2">认证说明:</h4>
        <ul className="list-disc pl-5 space-y-1">
          <li>我们会对您的个人信息严格保密</li>
          <li>请确保上传的身份证照片清晰可见</li>
          <li>认证审核通常需要1-3个工作日</li>
          <li>认证通过后不可修改个人信息</li>
        </ul>
      </div>
    </div>
  );
};

export default Verification;