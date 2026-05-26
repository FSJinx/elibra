import * as icons from 'lucide-vue-next'

export default {
  install(app) {
    for (const [name, component] of Object.entries(icons)) {
      app.component(name, component)
    }
  },
}
