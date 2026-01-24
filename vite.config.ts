import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';

// https://vite.dev/config/
export default defineConfig(() => {
  return {
    plugins: [
      laravel({
        input: ['resources/css/app.css', 'resources/js/app.tsx'],
        ssr: 'resources/js/ssr.tsx',
        refresh: true,
      }),
      react(),
      tailwindcss()
    ]
  }
})
