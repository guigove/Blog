import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { createHtmlPlugin } from 'vite-plugin-html'
import vueDevTools from 'vite-plugin-vue-devtools'
import path from 'path'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vueDevTools(),
    vue(),
    createHtmlPlugin({})
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src')
    }
  }
})
