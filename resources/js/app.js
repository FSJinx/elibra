// Font Source Poppins
import '@fontsource/poppins/300.css'
import '@fontsource/poppins/400.css'
import '@fontsource/poppins/500.css'
import '@fontsource/poppins/600.css'
import '@fontsource/poppins/700.css'
import '@fontsource/poppins/800.css'

// Tailwind Customization
import './assets/tailwind.css'

// CSS Customizations
import './assets/styles.css'

// Lucide Icons
import icons from './plugins/icons'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

const app = createApp(App)

app.config.devtools = false

app.use(createPinia())
app.use(router)
app.use(icons)
app.mount('#app')
