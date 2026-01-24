import { useState, useEffect } from 'react';

/**
 * 移动端检测Hook
 * 根据屏幕宽度检测设备类型，默认768px以下为移动端
 */
export const useMobile = (breakpoint: number = 768): boolean => {
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

/**
 * 响应式断点Hook
 * 支持多个断点检测
 */
export const useResponsive = () => {
  const [breakpoints, setBreakpoints] = useState({
    xs: false, // < 576px
    sm: false, // >= 576px
    md: false, // >= 768px
    lg: false, // >= 992px
    xl: false, // >= 1200px
    xxl: false, // >= 1600px
  });

  useEffect(() => {
    const checkBreakpoints = () => {
      const width = window.innerWidth;
      setBreakpoints({
        xs: width < 576,
        sm: width >= 576,
        md: width >= 768,
        lg: width >= 992,
        xl: width >= 1200,
        xxl: width >= 1600,
      });
    };

    checkBreakpoints();
    window.addEventListener('resize', checkBreakpoints);

    return () => {
      window.removeEventListener('resize', checkBreakpoints);
    };
  }, []);

  return breakpoints;
};