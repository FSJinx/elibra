interface AcquisitionParams {
  sort: string
  page: number
  per_page: number
  order: 'asc' | 'desc'
}

const defaultParams: Readonly<AcquisitionParams> = {
  sort: '',
  page: 1,
  per_page: 10,
  order: 'asc',
}

const url = 'acquisition'

export const useAcquisitionStore = defineStore('acquisition', {
  state: () => ({
    data: null as Acquisition[] | null,
    currentData: null as Acquisition | null,
    loading: false as true | false,
    params: defaultParams as AcquisitionParams,

    pop: usePopup(),
    auth: authStore(),
  }),

  getters: {},

  actions: {
    // =========== SETTERS ============
    pushData(data: Acquisition) {
      this.data?.push(data)
    },

    updateData(data: Acquisition) {
      this.data = (this.data as Acquisition[]).filter((i) => i.id !== data.id)
      nextTick(() => this.data?.push(data))
    },

    removeData(data: Acquisition) {
      this.data = (this.data as Acquisition[]).filter((i) => i.id !== data.id)
    },

    setData(data: Acquisition[]) {
      this.data = data
    },

    setCurrentData(data: Acquisition) {
      this.currentData = data
    },

    // =========== ACTIONS ============
    async fetch(forced = false) {
      if (this.data && !forced) return

      this.loading = true
      try {
        const res = await get(url)
        this.setData(res.data.data)
        return res
      } finally {
        this.loading = false
      }
    },

    async show(id: any) {
      const res = await get(`${url}/${id}`)
      this.setCurrentData(res?.data)
      return res
    },

    async create(params: Partial<Acquisition>) {
      const res = await post(url, params)
      this.pushData(res.data)

      return res
    },

    async update(params: Acquisition) {
      const res = await put(`${url}/${params.id}`, params)
      this.updateData(res.data)
      this.setCurrentData(res.data)
      return res
    },

    async destory(params: Acquisition) {
      const res = await del(`${url}/${params?.id}`)
      this.removeData(params)

      return res
    },

    async restore(params: Acquisition) {
      const res = await patch(`${url}/${params?.id}`)
      this.pushData(res.data)
      return res
    },
  },
})
