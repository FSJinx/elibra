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
      this.data = res.data?.data ?? []
      return res
    },

    async review(params: { patron: any; accession: any }) {
      const res = await get(`${this.url}/review`, params)
      return res
    },

    async checkout(params: { patron_id: any; accession_id: any; loan_mode_id: any }) {
      const res = await post(`${this.url}`, params)
      return res
    },

    async updateStatus(id: Loan['id'], status: Loan['status']) {
      return await put(`${this.url}/${id}`, { status })
    },

    async deleteLoan(id: Loan['id']) {
      return await del(`${this.url}/${id}`)
    },
  },
})
