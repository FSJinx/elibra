// Font Source Poppins
import '@fontsource/poppins'
import '@fontsource/poppins/300.css'
import '@fontsource/poppins/400.css'
import '@fontsource/poppins/500.css'
import '@fontsource/poppins/600.css'
import '@fontsource/poppins/700.css'
import '@fontsource/poppins/800.css'

// Font Source Inter
import '@fontsource/inter'
import '@fontsource/inter/300.css'
import '@fontsource/inter/400.css'
import '@fontsource/inter/500.css'
import '@fontsource/inter/600.css'
import '@fontsource/inter/700.css'
import '@fontsource/inter/800.css'

// Font Baskervville
import '@openfonts/baskervville_latin';

// Icons
// import '@flaticon/flaticon-uicons/css/regular/all.css'
import '@flaticon/flaticon-uicons/css/all/all.css'
import icons from '@/plugins/icons.js'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'

import App from './App.vue'
import router from '@/router/index.js'

const app = createApp(App)
const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)
;(app.config as any).devtools = false

app.use(pinia)
app.use(icons)
app.use(router)
app.mount('#e-Libra')
