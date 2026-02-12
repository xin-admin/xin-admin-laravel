import {useLocation, useOutlet} from "react-router";
import {useMemo, useRef} from "react";
import {SwitchTransition, CSSTransition} from "react-transition-group";
import './transition.css';
import useMenuStore from "@/stores/menu";

/**
 * 带动画的 Outlet 组件
 */
export default function AnimatedOutlet() {
  const location = useLocation();
  const currentOutlet = useOutlet();
  const nodeRef = useRef<HTMLDivElement>(null);
  const routeMap = useMenuStore(state => state.routeMap);

  // 获取所有嵌套路由的父级路径
  const nestedParentPaths = useMemo(() => {
    return Object.values(routeMap)
      .filter(route => route.type === 'nested-route')
      .map(route => route.path)
      .filter(Boolean) as string[];
  }, [routeMap]);

  // 计算动画 key
  const animationKey = useMemo(() => {
    const parentPath = nestedParentPaths.find(path => 
      location.pathname.startsWith(path + '/') || location.pathname === path
    );
    return parentPath || location.pathname;
  }, [location.pathname, nestedParentPaths]);

  return (
    <SwitchTransition mode="out-in">
      <CSSTransition
        key={animationKey}
        nodeRef={nodeRef}
        timeout={360}
        classNames="page"
        unmountOnExit
      >
        <div ref={nodeRef} className="page-wrapper">
          {currentOutlet}
        </div>
      </CSSTransition>
    </SwitchTransition>
  );
}