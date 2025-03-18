declare namespace API {
  interface ListResponse<T> {
    data: T[]
    page: number
    total: number
    per_page: number
    current_page: number
  }

  // 与后端约定的响应数据格式
  interface ResponseStructure<T> {
    success: boolean
    data: T
    errorCode?: number
    msg?: string
    showType?: ErrorShowType
    status?: number
    description?: string
    placement?: 'top' | 'topLeft' | 'topRight' | 'bottom' | 'bottomLeft' | 'bottomRight',
  }

}

