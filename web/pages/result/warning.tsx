import React from 'react';
import {Button, Card, Result} from 'antd';

const App: React.FC = () => (
  <Card variant={"borderless"}>
    <Result
      status="warning"
      title="There are some problems with your operation."
      extra={
        <Button type="primary" key="console">
          Go Console
        </Button>
      }
    />
  </Card>
);

export default App;