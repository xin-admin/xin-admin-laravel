/**
 * 表单验证
 */
import type {FormRule} from "antd";


/**
 * 必填
 */
export const verifyRequired: FormRule =  {
  required: true,
  message: '此项为必填项'
}

/**
 * 邮箱验证
 */
export const verifyEmail: FormRule =  {
  type: 'email',
  message: 'E-mail 输入错误!',
}

/**
 * Url 验证
 */
export const verifyUrl: FormRule =  {
  type: 'url',
  message: 'Url 输入错误!',
}

/**
 * 验证纯数字
 * @param form
 */
export const verifyNumber: FormRule = {
  type: 'number',
  message: '输入必须为纯数字',
}

/**
 * 验证纯数字
 * @param form
 */
export const verifyString: FormRule = {
  type: 'string',
  message: '输入必须为字符串',
}

/**
 * 验证整数
 * @param form
 */
export const verifyInteger: FormRule = {
  validator(_, value) {
    if (Number.isInteger(value) || !value) {
      return Promise.resolve();
    }
    return Promise.reject(new Error('该值必须为整数!'));
  }
}

/**
 * 手机号码验证
 */
export const verifyMobile: FormRule =  {
  validator(_, value) {
    if (/^(1[3-9])\d{9}$/.test(value.toString()) || !value) {
      return Promise.resolve();
    }
    return Promise.reject(new Error('手机号输入错误!'));
  }
}

/**
 * 身份证验证
 */
export const verifyIdCard: FormRule =  {
  validator(_, value) {
    if (/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/.test(value.toString()) || !value) {
      return Promise.resolve();
    }
    return Promise.reject(new Error('身份证输入错误!'));
  }
}