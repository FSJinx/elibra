interface PatronTypeParams extends BaseParams {}

const defaultParams: Readonly<Partial<PatronTypeParams>> = {
  query: '',
  order: 'desc',
  sort: 'created_at',
}

export const patronTypeStore = defineStore('patron_type', {
  state: () => ({
    data: null as PatronType[] | null,
    params: defaultParams as PatronTypeParams,

    url: 'patron-type',
  }),

  actions: {
    setData(data: PatronType[]) {
      this.data = data
    },

    async fetch(params = defaultParams) {
      const res = await get(this.url, params)
      this.setData(res.data.data)
      return res
    },
  },
})
