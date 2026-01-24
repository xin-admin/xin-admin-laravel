declare namespace Env {
  /** The router history mode */
  type RouterHistoryMode = 'hash' | 'history' | 'memory';

  /** Interface for import.meta */
  interface ImportMeta extends ImportMetaEnv {

    /** The base url of the application */
    readonly VITE_BASE_URL: string;

    readonly VITE_CONSTANT_ROUTE_MODE: 'dynamic' | 'static';

    readonly VITE_ROUTER_HISTORY_MODE?: RouterHistoryMode;
  }
}
