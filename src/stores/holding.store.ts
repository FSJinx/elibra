export const holdingStore = defineStore('holding', {
  state: () => ({
    data: null as Holding[] | null,
    currentData: null as Holding | null,

    url: 'holding',
  }),

  actions: {
    async fetch(params?: Partial<HoldingParams>) {
      const res = await get(this.url, params)
      this.data = res.data?.data
      return res
    },

    async create(payload: StoreHolding) {
      const res = await post(`${this.url}`, payload)
      return res
    },

    async complete(id: Holding['id'], loanModeId: number) {
      return await post(`${this.url}/${id}/complete`, { loan_mode_id: loanModeId })
    },

    async deleteHolding(params: Holding) {
      const res = await del(`${this.url}/${params.id}`)
      this.data = this.data?.filter((holding) => holding.id !== params.id) ?? []
      return res
    },
  },
})
