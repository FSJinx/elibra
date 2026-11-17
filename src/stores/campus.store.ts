export const campusStore = defineStore('campus', {
  state: () => ({
    data: null as Campus[] | null,
    currentData: null as Campus | null,

    url: 'campus',
  }),

  actions: {
    // =========== ACTIONS ============
    async fetch(params?: CampusParams) {
      const res = await get(this.url, params)
      this.data = res.data.data
      return res
    },

    async show(id: any) {
      const res = await get(`${this.url}/${id}`)
      this.currentData = res.data
      return res
    },

    async create(params: Partial<Campus>) {
      return await post(this.url, params)
    },

    async update(params: Campus) {
      return await put(`${this.url}/${params.id}`, params)
    },

    async remove(params: Campus) {
      return await del(`${this.url}/${params?.id}`)
    },

    async restore(params: Campus) {
      return await patch(`${this.url}/${params?.id}`)
    },
  },
})
