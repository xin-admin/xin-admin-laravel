import React from 'react';
import {Button, Card, Result} from 'antd';
import {useTranslation} from "react-i18next";
import {useNavigate} from "react-router";

const NotAuthorized: React.FC = () => {
  const { t } = useTranslation();
  const navigate = useNavigate();
  const onBack = () => {
    navigate('/');
  }

  return (
    <Card variant={"borderless"}>
      <Result
        status="403"
        title="403"
        subTitle={t('403.subTitle')}
        extra={(
          <Button type="primary" onClick={onBack}>{t('403.backHome')}</Button>
        )}
      />
    </Card>
  )
};

export default NotAuthorized;
