import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'
import { fileURLToPath, URL } from 'node:url'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '')

  return {
    plugins: [
      laravel({
        input: ['resources/styles/app.css', 'resources/src/app.ts'],
        refresh: true,
      }),
      tailwindcss(),
      vue(),
    ],

    resolve: {
      alias: {
        '@': fileURLToPath(new URL('./resources/src', import.meta.url)),
      },
    },

    server: {
      host: '0.0.0.0',
      port: 5173,
      hmr: {
        host: env.VITE_HMR_HOST || 'localhost',
      },
      watch: {
        ignored: ['**/storage/framework/views/**'],
      },
    },
  }
})
