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

    async show(id: string | number) {
      const res = await get(`${this.url}/${id}`)
      this.currentData = res.data
      return res
    },

    async create(params: StoreLoanMode) {
      const res = await post(this.url, params)
      const created = res.data as LoanMode
      this.data = this.data ? [...this.data, created] : [created]
      return res
    },

    async update (params: UpdateLoanMode) {
      const res = await put(`${this.url}/${params.id}`, params)
      if (this.data) {
        this.data = this.data.map((item) => item.id === params.id ? res.data : item)
      }
      if (this.currentData?.id === params.id) {
        this.currentData = res.data
      }
      return res
    },

    async remove(id: string | number) {
      const res = await del(`${this.url}/${id}`)
      this.data = this.data?.filter((item) => item.id !== id) ?? null
      if (this.currentData?.id === id) this.currentData = null
      return res
    },
  },
})
