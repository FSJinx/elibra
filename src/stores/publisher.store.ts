interface PublisherParams {
  sort: string
  page: number
  per_page: number
  order: 'asc' | 'desc'
}

const defaultParams: Readonly<PublisherParams> = {
  sort: '',
  page: 1,
  per_page: 10,
  order: 'asc',
}

const url = 'publisher'

export const useVendorStore = defineStore('vendor', {
  state: () => ({
    data: null as Publisher[] | null,
    currentData: null as Publisher | null,
    loading: false as true | false,
    params: defaultParams as PublisherParams,

    pop: usePopup(),
    auth: authStore(),
  }),

  getters: {
    select() {
      return () => this.data?.sort((a, b) => a.name.localeCompare(b.name))
    },
  },

  actions: {
    // =========== SETTERS ============
    pushData(data: Publisher) {
      this.data?.push(data)
    },

    updateData(data: Publisher) {
      this.data = (this.data as Publisher[]).filter((i) => i.id !== data.id)
      nextTick(() => this.data?.push(data))
    },

    removeData(data: Publisher) {
      this.data = (this.data as Publisher[]).filter((i) => i.id !== data.id)
    },

    setData(data: Publisher[]) {
      this.data = data
    },

    setCurrentData(data: Publisher) {
      this.currentData = data
    },

    // =========== ACTIONS ============
    async fetch(forced = false) {
      if (this.data && !forced) return

      this.loading = true
      const res = await get(url)
      this.setData(res.data)
      this.loading = false

      return res
    },

    async show(id: any) {
      const res = await get(`${url}/${id}`)
      this.setCurrentData(res?.data)
      return res
    },

    async create(params: Partial<Publisher>) {
      const res = await post(url, params)
      this.pushData(res.data)

      return res
    },

    async update(params: Publisher) {
      const res = await put(`${url}/${params.id}`, params)
      this.updateData(res.data)
      this.setCurrentData(res.data)
      return res
    },

    async destory(params: Publisher) {
      const res = await del(`${url}/${params?.id}`)
      this.removeData(params)

      return res
    },

    async restore(params: Publisher) {
      const res = await patch(`${url}/${params?.id}`)
      this.pushData(res.data)
      return res
    },
  },
})
