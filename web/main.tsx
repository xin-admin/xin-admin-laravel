import ReactDOM from "react-dom/client";
import App from "./App";
import '@ant-design/v5-patch-for-react-19';
import './index.css';
import '@/locales/i18n';
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";

dayjs.extend(relativeTime);

const container = document.getElementById('root')!;

const root = ReactDOM.createRoot(container);

root.render(<App/>);
