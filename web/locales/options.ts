import zh_CN from "antd/es/locale/zh_CN";
import en_US from "antd/es/locale/en_US";
import 'dayjs/locale/en';
import 'dayjs/locale/zh-cn';

export default [
  { label: '简体中文', value: 'zh', antdLocale: zh_CN, dayjsLocale: 'zh-cn' },
  { label: 'English', value: 'en', antdLocale: en_US, dayjsLocale: 'en' },
]