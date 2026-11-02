export const patronStore = defineStore('patron', {
  state: () => ({
    data: null as Patron[] | null,
    currentData: null as Patron | null,

    url: 'patron',
  }),

  actions: {
    async fetch(params: Partial<PatronParams>) {
      const res = await get(`${this.url}`, params)
      this.data = res.data?.data
      return res
    },
  },
})
