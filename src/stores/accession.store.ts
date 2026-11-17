export const accessionStore = defineStore('accession', {
  state: () => ({
    data: null as Accession[] | null,
    currentData: null as Accession | null,

    url: 'accession',
  }),

  actions: {
    async fetch(params?: Partial<AccessionParams>) {
      const res = await get(`${this.url}`, params)
      this.data = res.data?.data
      return res
    },

    async create(payload: StoreAccession) {
      const res = await post(`${this.url}`, payload)
      return res
    },

    async update(payload: UpdateAccession) {
      const res = await put(`${this.url}/${payload.id}`, payload)
      return res
    },

    // =========== OTHERS ==============

    async getLastAccessionNumber(payload: Accession) {
      const res = await put(`${this.url}/${payload.id}`, payload)
      return res
    },
  },
})
