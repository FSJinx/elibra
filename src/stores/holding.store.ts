export const holdingStore = defineStore('holding', {
  state: () => ({
    data: null as Holding[] | null,
    currentData: null as Holding | null,

    url: 'holding',
  }),

  actions: {
    async complete(params: Holding & { loan_mode_id: string }) {
      const res = await post(`${this.url}/${params.id}/complete`, params)
      return res
    },

    async deleteHolding(params: Holding) {
      const res = await del(`${this.url}/${params.id}`)
      return res
    },
  },
})
