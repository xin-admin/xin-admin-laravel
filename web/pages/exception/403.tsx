import React from 'react';
import {Button, Card, Result} from 'antd';

const App: React.FC = () => (
  <Card variant={"borderless"}>
    <Result
      status="403"
      title="403"
      subTitle="Sorry, you are not authorized to access this page."
      extra={<Button type="primary">Back Home</Button>}
    />
  </Card>
);

export default App;