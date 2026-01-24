import Layout from '@/layout';
import type {ReactNode} from "react";

const Dashboard = () => {

  return (
    <>
      仪表盘
    </>
  )
}

Dashboard.layout = (page: ReactNode) => <Layout children={page}/>

export default Dashboard;
