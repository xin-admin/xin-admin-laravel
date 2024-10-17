import { ProLayoutProps } from '@ant-design/pro-components';
import { QuestionCircleOutlined } from '@ant-design/icons';
import { SelectLang } from '@@/exports';
import Fullscreen from '@/components/Layout/Fullscreen';

const ActionsRender: ProLayoutProps['actionsRender'] = (props) => {

  if (typeof window === 'undefined') return [];
  if (props.isMobile) return [];

  return [
    <QuestionCircleOutlined key={'QuestionCircleOutlined'} onClick={() => {
      window.open('https://doc.xinadmin.cn');
    }} />,
    <SelectLang key={'SelectLang'} reload={false} />,
    <Fullscreen key={'Fullscreen'}></Fullscreen>,
  ];
};

export default ActionsRender;
