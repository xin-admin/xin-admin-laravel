import {useLocation, useOutlet} from "react-router";
import {useMemo, useRef} from "react";
import {SwitchTransition, CSSTransition} from "react-transition-group";
import './transition.css';
import useMenuStore from "@/stores/menu";

/**
 * 带动画的 Outlet 组件
 * 使用 react-transition-group 实现路由切换动画
 */
export default function AnimatedOutlet() {
  const location = useLocation();
  const currentOutlet = useOutlet();
  const nodeRef = useRef<HTMLDivElement>(null);
  const menuMap = useMenuStore(state => state.menuMap);

  // 查找所有 menuMap 中 type 为 嵌套路由的 pathname
  const nestedRoutes = useMemo(() => {
    return Object.values(menuMap)
      .filter(menu => menu.type === 'nested-route')
      .map(menu => menu.path);
  }, [menuMap]);

  if (nestedRoutes.includes(location.pathname)) {
    return currentOutlet;
  }

  return (
    <SwitchTransition mode="out-in">
      <CSSTransition
        key={location.pathname}
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