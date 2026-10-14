interface VendorParams {
  sort: string
  page: number
  per_page: number
  order: 'asc' | 'desc'
}

const defaultParams: Readonly<VendorParams> = {
  sort: '',
  page: 1,
  per_page: 10,
  order: 'asc',
}

const url = 'vendor'

export const useVendorStore = defineStore('vendor', {
  state: () => ({
    data: null as Vendor[] | null,
    currentData: null as Vendor | null,
    loading: false as true | false,
    params: defaultParams as VendorParams,

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
    pushData(data: Vendor) {
      this.data?.push(data)
    },

    updateData(data: Vendor) {
      this.data = (this.data as Vendor[]).filter((i) => i.id !== data.id)
      nextTick(() => this.data?.push(data))
    },

    removeData(data: Vendor) {
      this.data = (this.data as Vendor[]).filter((i) => i.id !== data.id)
    },

    setData(data: Vendor[]) {
      this.data = data
    },

    setCurrentData(data: Vendor) {
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

    async create(params: Partial<Vendor>) {
      const res = await post(url, params)
      this.pushData(res.data)

      return res
    },

    async update(params: Vendor) {
      const res = await put(`${url}/${params.id}`, params)
      this.updateData(res.data)
      this.setCurrentData(res.data)
      return res
    },

    async destory(params: Vendor) {
      const res = await del(`${url}/${params?.id}`)
      this.removeData(params)

      return res
    },

    async restore(params: Vendor) {
      const res = await patch(`${url}/${params?.id}`)
      this.pushData(res.data)
      return res
    },
  },
})
