export const circulationStore = defineStore('circulation', {
  state: () => ({
    data: null as Circulation[] | null,
    currentData: null as Circulation | null,

    url: 'circulation', 
    params: {} as CirculationParams,  
  }),

  actions: {
    async fetch(params?: Partial<CirculationParams>) {
      const res = await get(`${this.url}`, params)
      this.data = res.data?.data ?? []
      return res
    },

    async checkOut(payload: CheckOutPayload) {
      const res = await post(`${this.url}`, payload)
      return res
    },

    async checkIn(payload: Circulation) {
      const res = await post(`${this.url}/${payload.id}/return`)
      return res
    },

    async renew(payload: Circulation) {
      const res = await post(`${this.url}/${payload.id}/renew`)
      return res
    },

    async remove(payload: Circulation) {
      const res = await del(`${this.url}/${payload.id}`)
      return res
    },
  },
})
