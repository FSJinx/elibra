import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'
import { fileURLToPath, URL } from 'node:url'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/styles/app.css', 'resources/frontend/app.js'],
      refresh: true,
    }),
    tailwindcss(),
    vue(),
  ],

  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./resources/frontend', import.meta.url)),
    },
  },

  server: {
    host: '0.0.0.0',
    port: 5173,
    hmr: {
      host: '192.168.100.181',
    },
    watch: {
      ignored: ['**/storage/framework/views/**'],
    },
  },
})
