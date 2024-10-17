import { useModel } from '@umijs/max';
import { SettingDrawer } from '@ant-design/pro-components';


const SettingLayout: React.FC = () => {

  const { initialState, setInitialState } = useModel('@@initialState');


  return (
    <SettingDrawer
      getContainer={(e: any) => {
        if (typeof window === 'undefined') return e;
        return document.getElementById('test-pro-layout');
      }}
      disableUrlParams
      enableDarkTheme
      settings={initialState?.settings}
      onSettingChange={(changeSetting) => {
        setInitialState((preInitialState: any) => ({
          ...preInitialState,
          settings: {...initialState?.settings ,...changeSetting},
        }));
      }}
    />
  )
}

export default SettingLayout
