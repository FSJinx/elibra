interface PatronTypeLoanPolicyParams extends BaseParams {
  loan_mode_id: any
}

const defaultParams: Partial<PatronTypeLoanPolicyParams> = {
  query: '',
}

export const patronTypeLoanPolicyStore = defineStore('patron_type_loan_policy', {
  state: () => ({
    data: null as PatronTypeLoanPolicy[] | null,
    currentData: null as PatronTypeLoanPolicy | null,
    params: defaultParams,

    url: 'patron-type-loan-policy',
  }),

  actions: {
    async fetch(params?: Partial<PatronTypeLoanPolicyParams>) {
      const res = await get(`${this.url}`, params)
      this.data = res.data.data
      return res
    },

    async update(params?: UpdatePatronTypeLoanPolicy) {
      const res = await get(`${this.url}`, params)
      return res
    },
  },
})
