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
    
    async checkout(params: {patron_id: any, accession_number: any, loan_mode_id: any}) {
      const res = await post(`${this.url}`, params)
      return res
    }
  },
})
