import { defineStore } from 'pinia'

export const useBackdrop = defineStore('backdrop', {
  state: () => ({
    show: false,

    resolver: null,
  }),

  actions: {
    close() {},
  },
})
