import * as icons from '@lucide/vue'

export default {
  install(app: any) {
    for (const [name, component] of Object.entries(icons)) {
      app.component(name, component)
    }
  },
}
