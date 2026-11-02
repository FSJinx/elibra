export const accessionStore = defineStore('accession', {
    state: () => ({
    data: null as Accession[] | null,
    currentData: null as Accession | null,

    url: 'accession',
  }),

  actions: {
    async fetch(params: Partial<AccessionParams>) {
      const res = await get(`${this.url}`, params)
      this.data = res.data?.data
      return res
    },
  },
})