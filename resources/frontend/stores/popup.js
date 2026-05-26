import { defineStore } from 'pinia'

export const usePopup = defineStore('popup', {
  state: () => ({
    show: false,
    component: null,
    props: {},

    resolver: null,
  }),

  actions: {
    open(component, props = {}) {
      this.show = true
      this.component = component
      this.props = props

      return true
    },

    close() {
      this.show = false
      this.component = null
      this.props = {}

      return false
    },
  },
})
