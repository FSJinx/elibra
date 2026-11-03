const defaultParams: Readonly<Partial<AcquisitionParams>> = {
  sort: '',
  page: 1,
  per_page: '25',
  order: 'asc',
  acquisition_mode_id: '',
}

export const acquisitionStore = defineStore('acquisition', {
  state: () => ({
    data: null as Acquisition[] | null,
    currentData: null as Acquisition | null,
    params: defaultParams as AcquisitionParams,

    url: 'acquisition',
  }),

  getters: {},

  actions: {
    // =========== ACTIONS ============
    async fetch(params?: Partial<AcquisitionParams>) {
      const res = await get(this.url, params)
      this.data = res.data?.data
      return res
    },

    async show(id: any) {
      const res = await get(`${this.url}/${id}`)
      this.currentData = res.data
      return res
    },

    async create(params: Partial<Acquisition>) {
      const res = await post(this.url, params)
      return res
    },

    async update(params: Acquisition) {
      const res = await put(`${this.url}/${params.id}`, params)
      return res
    },

    async destory(params: Acquisition) {
      const res = await del(`${this.url}/${params?.id}`)
      return res
    },

    async restore(params: Acquisition) {
      const res = await patch(`${this.url}/${params?.id}`)
      return res
    },
  },
})
