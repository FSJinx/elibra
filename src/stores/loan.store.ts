export const loanStore = defineStore('loan', {
  state: () => ({
    data: null,

    url: 'loan',
  }),

  actions: {
    async review(params: { patron: any; accession: any }) {
      const res = await get(`${this.url}/review`, params)
      return res
    },
  },
})
