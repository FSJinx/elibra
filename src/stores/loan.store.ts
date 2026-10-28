export interface LoanParams extends BaseParams {
  library_id: any
}

export const loanStore = defineStore('loan', {
  state: () => ({
    data: null as Loan[] | null,
    currentData: null as Loan | null,

    url: 'loan',
  }),

  actions: {
    async fetch(params?: Partial<LoanParams>) {
      const res = await get(`${this.url}`, params)
      return res
    },

    async review(params: { patron: any; accession: any }) {
      const res = await get(`${this.url}/review`, params)
      return res
    },

    async checkout(params: { patron_id: any; accession_number: any; loan_mode_id: any }) {
      const res = await post(`${this.url}`, params)
      return res
    },
  },
})
