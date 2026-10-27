interface LoanModeParams extends BaseParams {}

const defaultParams: Partial<LoanModeParams> = {
  query: '',
}

export const loanModeStore = defineStore('loan_modes', {
  state: () => ({
    data: null as LoanMode[] | null,
    currentData: null as LoanMode | null,
    params: defaultParams,

    url: 'loan-mode',
  }),

  actions: {
    async fetch(params?: Partial<LoanModeParams>) {
      const res = await get(`${this.url}`, params)
      this.data = res.data.data
      return res
    },

    async show(data: Partial<LoanMode>) {
      const res = await get(`${this.url}/${data.id}`)
      this.currentData = res.data
      return res
    },

    async create(params: StoreLoanMode) {
      const res = await post(this.url, params)
      return res
    },

    async update (params: UpdateLoanMode) {
      const res = await put(`${this.url}/${params.id}`, params)
      return res
    }
  },
})
