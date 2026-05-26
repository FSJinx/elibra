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
    watch: {
      ignored: ['**/storage/framework/views/**'],
    },
  },
})
