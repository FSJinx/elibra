export const opacStore = defineStore('opac', {
  state: () => ({
    data: null as Opac[] | null,
    currentData: null as Opac | null,

    url: 'opac',
  }),

  actions: {
    async fetch(params: Partial<OpacParams>) {
      const res = await get(`${this.url}`, params)
      this.data = res.data?.data
      return res
    },

    async show(id: any) {
      const res = await get(`${this.url}/${id}`)
      this.currentData = res.data
      return res
    },
  },
})
