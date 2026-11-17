export const reportStore = defineStore('reports', {
  state: () => ({
    data: null as Report[] | null,
    currentData: null as Report | null,

    url: 'report',
  }),

  actions: {
    async fetch(params: Partial<ReportParams>) {
      const res = await get(`${this.url}`, params)
      this.data = res.data.data
      return res
    },

    async store(payload: Partial<StoreReport>) {
      const res = await post(`${this.url}`, payload)
      return res
    },
  },
})
