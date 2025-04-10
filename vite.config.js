// vite.config.js
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  server: {
    host: '0.0.0.0',  // 設定為 localhost 或其他具體的 IP 地址
  },
  plugins: [vue()],
  build: {
    rollupOptions: {
      input: {
        index: './index.html',
        admin: './admin.html',
        storebackground: './storebackground.html',
        background: './background.html',
      },
    },
  },
});