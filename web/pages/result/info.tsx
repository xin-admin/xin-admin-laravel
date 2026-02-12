import React from 'react';
import {Button, Card, Result} from 'antd';

const App: React.FC = () => (
  <Card variant={"borderless"}>
    <Result
      title="Your operation has been executed"
      extra={
        <Button type="primary" key="console">
          Go Console
        </Button>
      }
    />
  </Card>
);

export default App;