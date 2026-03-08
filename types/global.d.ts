interface Window {

  tokenRefreshing?: boolean;

  requests?: any[];

  /** Ant-design-vue message instance */
  $message?: import('antd/es/message/interface').MessageInstance;

  /** Ant-design-vue modal instance */
  $modal?: Omit<import('antd/es/modal/confirm').ModalStaticFunctions, 'warn'>;

  /** Ant-design-vue notification instance */
  $notification?: import('antd/es/notification/interface').NotificationInstance;

}

interface anyObj {
  [key: string]: any
}