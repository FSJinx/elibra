export const publisherStore = defineStore('publisher', {
  state: () => ({
    data: null as Publisher[] | null,
    currentData: null as Publisher | null,

    url: 'publisher',
  }),

  actions: {
    // =========== ACTIONS ============
    async fetch(params: PublisherParams) {
      const res = await get(this.url, params)
      this.data = res.data.data
      return res
    },

    async show(id: any) {
      const res = await get(`${this.url}/${id}`)
      this.currentData = res.data
      return res
    },

    async create(params: Partial<Publisher>) {
      const res = await post(this.url, params)
      return res
    },

    async update(params: Publisher) {
      const res = await put(`${this.url}/${params.id}`, params)
      return res
    },

    async destory(params: Publisher) {
      const res = await del(`${this.url}/${params?.id}`)
      return res
    },

    async restore(params: Publisher) {
      const res = await patch(`${this.url}/${params?.id}`)
      return res
    },
  },
})
