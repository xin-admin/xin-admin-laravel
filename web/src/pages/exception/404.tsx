import React from 'react';
import {Button, Card, Result} from 'antd';

const App: React.FC = () => (
  <Card variant={"borderless"}>
    <Result
      status="404"
      title="404"
      subTitle="Sorry, the page you visited does not exist."
      extra={<Button type="primary">Back Home</Button>}
    />
  </Card>

);

export default App;