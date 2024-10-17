import { FullscreenExitOutlined, FullscreenOutlined } from '@ant-design/icons';
import React from 'react';
import { useBoolean } from 'ahooks';

export default () => {
  const [fullscreen, setFullscreen] = useBoolean(false);
  const onClick = () => {
    /* 获取 documentElement (<html>) 以全屏显示页面 */
    let elem = document.documentElement;
    /* 全屏查看 */
    if (document.fullscreenElement) {
      document.exitFullscreen().then(() => {
        setFullscreen.setFalse();
      });
    } else {
      elem.requestFullscreen().then(() => {
        setFullscreen.setTrue();
      });
    }
  };

  return (
    <>
      {fullscreen ?
        <FullscreenExitOutlined title={'退出全屏'} onClick={onClick} />
        :
        <FullscreenOutlined title={'全屏显示'} onClick={onClick} />
      }
    </>
  );
}
