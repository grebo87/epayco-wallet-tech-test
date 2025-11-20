import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

const API_PORT = process.env.VITE_API_BASE || 'http://localhost:8002'

export default defineConfig({
  plugins: [vue()],
  server: {
    port: 5174,
    proxy: {
      // proxyear llamadas /api al backend para evitar CORS en dev
      '/api': {
        target: API_PORT,
        changeOrigin: true,
        secure: false,
        rewrite: (path) => path
      }
    }
  }
})
