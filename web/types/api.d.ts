declare namespace API {

  type ErrorShowType =
    | 0      // Success Message
    | 1      // Warning Message
    | 2      // Error Message
    | 3      // Success Notification
    | 4      // Warning Notification
    | 5      // Error Notification
    | 99;    // Other

  /** List response data format */
  type ListResponse<T> = {
    data: T[]
    page: number
    total: number
    per_page: number
    current_page: number
  }

  /** The response data format agreed upon with the back end */
  interface ResponseStructure<T = any> {
    success: boolean
    msg: string
    data?: T
    errorCode?: number
    showType?: ErrorShowType
    status?: number
    description?: string
    placement?: 'top' | 'topLeft' | 'topRight' | 'bottom' | 'bottomLeft' | 'bottomRight',
  }
}