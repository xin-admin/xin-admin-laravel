import { Avatar } from 'antd';
import React from 'react';
import { createStyles } from 'antd-style';
import { IAdminUserList } from '@/domain/iAdminList';

const useStyle = createStyles(({ token, css }) => {
  return {
    userInfo: css`
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px 0;
        border-bottom: 1px solid ${token.colorBorder}
    `,
    avatar: css`
        margin-bottom: 10px;
    `,
    nickname: css`
        font-size: 20px;
    `,
    infoItems: css`
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid ${token.colorBorderSecondary};
        padding: 10px;
    `,
    title: css`
        color: ${token.colorTextDescription};
    `
  };
});

export default (props: { userInfo?: IAdminUserList }) => {
  const { userInfo } = props;
  const { styles } = useStyle();

  return (
    <div style={{ paddingBottom: 20 }}>
      <div className={styles.userInfo}>
        <Avatar className={styles.avatar} size={80} src={userInfo?.avatar_url} />
        <div className={styles.nickname}>{userInfo?.nickname}</div>
      </div>
      <div className={styles.infoItems}>
        <div className={styles.title}>用户名</div>
        <div>{userInfo?.username}</div>
      </div>
      <div className={styles.infoItems}>
        <div className={styles.title}>昵称</div>
        <div>{userInfo?.nickname}</div>
      </div>
      <div className={styles.infoItems}>
        <div className={styles.title}>邮箱</div>
        <div>{userInfo?.email}</div>
      </div>
      <div className={styles.infoItems}>
        <div className={styles.title}>手机号</div>
        <div>{userInfo?.mobile}</div>
      </div>
      <div className={styles.infoItems}>
        <div className={styles.title}>部门</div>
        <div>{userInfo?.dept_name}</div>
      </div>
      <div className={styles.infoItems}>
        <div className={styles.title}>角色</div>
        <div>{userInfo?.role_name}</div>
      </div>
    </div>
  );
};
