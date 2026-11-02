export const circulationStore = defineStore('circulation', {
  state: () => ({
    data: null as Circulation[] | null,
    currentData: null as Circulation | null,

    url: 'circulation',
  }),

  actions: {
    async fetch(params?: Partial<CirculationParams>) {
      const res = await get(`${this.url}`, params)
      this.data = res.data?.data ?? []
      return res
    },

    async borrow(payload?: Borrow) {
      const res = await post(`${this.url}`, payload)
      return res
    },
  },
})
