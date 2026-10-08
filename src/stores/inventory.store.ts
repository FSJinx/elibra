interface InventoryParams {
  sort: string
  page: number
  per_page: number
  order: 'asc' | 'desc'
}

const defaultParams: Readonly<InventoryParams> = {
  sort: '',
  page: 1,
  per_page: 10,
  order: 'asc',
}

const url = 'inventory'

export const useInventoryStore = defineStore('inventory', {
  state: () => ({
    data: null as Inventory[] | null,
    currentData: null as Inventory | null,
    loading: false as true | false,
    params: defaultParams as InventoryParams,

    pop: usePopup(),
    auth: authStore(),
  }),

  getters: {
    
  },

  actions: {
    // =========== SETTERS ============
    pushData(data: Inventory) {
      this.data?.push(data)
    },

    updateData(data: Inventory) {
      this.data = (this.data as Inventory[]).filter((i) => i.id !== data.id)
      nextTick(() => this.data?.push(data))
    },

    removeData(data: Inventory) {
      this.data = (this.data as Inventory[]).filter((i) => i.id !== data.id)
    },

    setData(data: Inventory[]) {
      this.data = data
    },

    setCurrentData(data: Inventory) {
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

    async create(params: Partial<Inventory>) {
      const res = await post(url, params)
      this.pushData(res.data)

      return res
    },

    async update(params: Inventory) {
      const res = await put(`${url}/${params.id}`, params)
      this.updateData(res.data)
      this.setCurrentData(res.data)
      return res
    },

    async destory(params: Inventory) {
      const res = await del(`${url}/${params?.id}`)
      this.removeData(params)

      return res
    },

    async restore(params: Inventory) {
      const res = await patch(`${url}/${params?.id}`)
      this.pushData(res.data)
      return res
    },
  },
})
