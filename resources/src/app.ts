// Font Source Poppins
import '@fontsource/poppins'
// import '@fontsource/poppins/300.css'
// import '@fontsource/poppins/400.css'
// import '@fontsource/poppins/500.css'
// import '@fontsource/poppins/600.css'
// import '@fontsource/poppins/700.css'
// import '@fontsource/poppins/800.css'

// Font Source Inter
import '@fontsource/inter'
// import '@fontsource/inter/300.css'
// import '@fontsource/inter/400.css'
// import '@fontsource/inter/500.css'
// import '@fontsource/inter/600.css'
// import '@fontsource/inter/700.css'
// import '@fontsource/inter/800.css'

// Icons
import '@flaticon/flaticon-uicons/css/regular/all.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from '@/app/router'
import api from '@/app/plugins/axios'

const app = createApp(App)
const pinia = createPinia()

;(app.config as any).devtools = false
app.config.globalProperties.$api = api

app.use(pinia)
app.use(router)
app.mount('#app')
