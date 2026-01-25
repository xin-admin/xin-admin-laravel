import '../css/app.css';
import { StyleProvider } from '@ant-design/cssinjs';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';

const appName = import.meta.env.VITE_APP_NAME || 'XinAdmin';

createInertiaApp({
  title: (title) => (title ? `${title} - ${appName}` : appName),
  resolve: (name) =>
    resolvePageComponent(
      `./pages/${name}.tsx`,
      import.meta.glob('./pages/**/*.tsx'),
    ),
  setup({ el, App, props }) {
    const root = createRoot(el);

    root.render(
      <StrictMode>
        <StyleProvider layer>
          <App {...props} />
        </StyleProvider>
      </StrictMode>,
    );
  },
  progress: {
    color: '#4B5563',
  },
});
