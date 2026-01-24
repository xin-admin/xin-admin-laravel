import { useEffect, useRef, useState } from 'react';

/**
 * 主题切换过渡动画 Hook
 */
export const useThemeTransition = () => {
  const [isTransitioning, setIsTransitioning] = useState(false);
  const transitionTimeoutRef = useRef<NodeJS.Timeout>(null);

  // 清理定时器
  useEffect(() => {
    return () => {
      if (transitionTimeoutRef.current) {
        clearTimeout(transitionTimeoutRef.current);
      }
    };
  }, []);

  /**
   * 执行主题切换动画
   * @param updateTheme 更新主题的回调函数
   */
  const transitionTheme = async (updateTheme: () => void) => {
    // 检查浏览器是否支持 View Transitions API
    if (!document.startViewTransition) {
      updateTheme();
      return;
    }

    setIsTransitioning(true);

    try {
      // 使用 View Transitions API 执行过渡
      const transition = document.startViewTransition(() => {
        updateTheme();
      });

      await transition.ready;
      
      // 过渡完成后重置状态
      transitionTimeoutRef.current = setTimeout(() => {
        setIsTransitioning(false);
      }, 300);
    } catch (error) {
      console.warn('Theme transition failed:', error);
      setIsTransitioning(false);
    }
  };

  /**
   * 圆形扩散动画（从点击位置扩散）
   * @param event 鼠标事件
   * @param updateTheme 更新主题的回调函数
   */
  const transitionThemeWithCircle = async (
    event: React.MouseEvent,
    updateTheme: () => void
  ) => {
    if (!document.startViewTransition) {
      updateTheme();
      return;
    }

    const x = event.clientX;
    const y = event.clientY;
    const endRadius = Math.hypot(
      Math.max(x, window.innerWidth - x),
      Math.max(y, window.innerHeight - y)
    );

    setIsTransitioning(true);

    try {
      const transition = document.startViewTransition(() => {
        updateTheme();
      });

      await transition.ready;

      // 应用圆形扩散动画
      document.documentElement.animate(
        {
          clipPath: [
            `circle(0px at ${x}px ${y}px)`,
            `circle(${endRadius}px at ${x}px ${y}px)`,
          ],
        },
        {
          duration: 500,
          easing: 'ease-in-out',
          pseudoElement: '::view-transition-new(root)',
        }
      );

      transitionTimeoutRef.current = setTimeout(() => {
        setIsTransitioning(false);
      }, 500);
    } catch (error) {
      console.warn('Theme transition failed:', error);
      setIsTransitioning(false);
    }
  };

  return {
    isTransitioning,
    transitionTheme,
    transitionThemeWithCircle,
  };
};
