import {useTranslation} from 'react-i18next';
import {useMemo} from 'react';
import dayjs from 'dayjs';
import options from '@/locales/options';

/**
 * 语言切换 Hook
 */
const useLanguage = () => {
  const { i18n } = useTranslation();

  /**
   * 切换语言
   * @param lng 语言代码
   */
  const changeLanguage = async (lng: string) => {
    const config = getLanguageConfig(lng);
    dayjs.locale(config.dayjsLocale);
    await i18n.changeLanguage(lng);
    localStorage.setItem('i18nextLng', lng);
  };

  /**
   * 获取当前语言配置对象
   */
  const getLanguageConfig = (lng: string) => {
    return options.find(opt => opt.value === lng) || options[0];
  };

  /**
   * Antd Locale Hook
   */
  const antdLocale = useMemo(() => {
    const currentLang = i18n.language;
    const config = options.find(opt => opt.value === currentLang);
    return config ? config.antdLocale : options[0].antdLocale;
  }, [i18n.language]);

  return {
    antdLocale,
    language: i18n.language,
    changeLanguage,
    getLanguageConfig,
    languageOptions: options,
  };
};

export default useLanguage;