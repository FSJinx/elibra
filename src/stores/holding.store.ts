export const holdingStore = defineStore('holding', {
  state: () => ({
    data: null as Holding[] | null,
    currentData: null as Holding | null,
    loading: false,

    url: 'holding',
  }),

  actions: {
    async fetch() {
      this.loading = true
      try {
        const res = await get(this.url)
        this.data = res.data?.data ?? []
        return res
      } finally {
        this.loading = false
      }
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
