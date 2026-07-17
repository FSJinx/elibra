import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'
import { fileURLToPath, URL } from 'node:url'
import tailwindcss from '@tailwindcss/vite'
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'

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

      AutoImport({
        dts: 'resources/src/auto-import.d.ts',

        imports: ['vue', 'vue-router', 'pinia'],

        dirs: ['resources/src/composables/**', 'resources/src/stores/**', 'resources/src/router/*'],

        vueTemplate: true,
      }),

      Components({
        dirs: ['resources/src/components/**'],

        dts: 'resources/src/components/components.d.ts',
      }),
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
