import { useState, useEffect } from 'react';

/**
 * 移动端检测Hook
 * 根据屏幕宽度检测设备类型，默认768px以下为移动端
 */
const useMobile = (breakpoint: number = 768): boolean => {
  const [isMobile, setIsMobile] = useState<boolean>(false);

  useEffect(() => {
    const checkMobile = () => {
      setIsMobile(window.innerWidth < breakpoint);
    };

    // 初始检测
    checkMobile();

    // 监听窗口大小变化
    window.addEventListener('resize', checkMobile);
    window.addEventListener('orientationchange', checkMobile);

    return () => {
      window.removeEventListener('resize', checkMobile);
      window.removeEventListener('orientationchange', checkMobile);
    };
  }, [breakpoint]);

  return isMobile;
};

export default useMobile;
